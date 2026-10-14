import { Fragment, useEffect, useState } from 'react';
import { api } from '../api';
import ImageUploadField from '../components/ImageUploadField';

const BLANK = { caption: '', categories: '', image_path: '' };

export default function GalleryManager() {
  const [rows, setRows] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [editingId, setEditingId] = useState(null); // null | 'new' | <id>
  const [draft, setDraft] = useState(BLANK);
  const [saving, setSaving] = useState(false);

  async function load() {
    setLoading(true);
    setError('');
    try {
      setRows((await api.getGalleryItems()).data);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  }

  useEffect(() => { load(); }, []);

  function startEdit(row) {
    setEditingId(row.id);
    setDraft({ ...row });
  }

  function startNew() {
    setEditingId('new');
    setDraft(BLANK);
  }

  function cancelEdit() {
    setEditingId(null);
    setDraft(BLANK);
  }

  async function save() {
    if (!draft.image_path) {
      setError('An image is required.');
      return;
    }
    setSaving(true);
    setError('');
    try {
      if (editingId === 'new') {
        await api.createGalleryItem(draft);
      } else {
        await api.updateGalleryItem(editingId, draft);
      }
      cancelEdit();
      await load();
    } catch (err) {
      setError(err.message);
    } finally {
      setSaving(false);
    }
  }

  async function remove(id) {
    if (!window.confirm('Delete this image? This cannot be undone.')) return;
    try {
      await api.deleteGalleryItem(id);
      await load();
    } catch (err) {
      setError(err.message);
    }
  }

  async function move(id, direction) {
    try {
      await api.moveGalleryItem(id, direction);
      await load();
    } catch (err) {
      setError(err.message);
    }
  }

  return (
    <div className="inbox">
      <div className="inbox-toolbar">
        <h2 className="panel-heading">Gallery</h2>
        <button className="btn-primary" onClick={startNew}>+ Add Image</button>
      </div>

      {error && <div className="alert alert-error">{error}</div>}

      {editingId !== null && (
        <div className="edit-panel">
          <h3>{editingId === 'new' ? 'New Gallery Image' : 'Edit Gallery Image'}</h3>
          <ImageUploadField label="Image*" folder="gallery" value={draft.image_path} onChange={(path) => setDraft({ ...draft, image_path: path })} />
          <label>Caption<input value={draft.caption || ''} onChange={(e) => setDraft({ ...draft, caption: e.target.value })} /></label>
          <label>
            Categories (comma separated, e.g. "Private Dining, Chef Portraits")
            <input value={draft.categories || ''} onChange={(e) => setDraft({ ...draft, categories: e.target.value })} />
          </label>
          <p className="muted small">New categories appear as filter buttons on the public gallery automatically — no extra setup needed.</p>
          <div className="edit-panel-actions">
            <button className="btn-primary" onClick={save} disabled={saving}>{saving ? 'Saving…' : 'Save'}</button>
            <button onClick={cancelEdit} disabled={saving}>Cancel</button>
          </div>
        </div>
      )}

      {loading ? (
        <p className="muted">Loading…</p>
      ) : rows.length === 0 ? (
        <p className="muted">No gallery images yet.</p>
      ) : (
        <table className="data-table">
          <thead>
            <tr><th>Image</th><th>Caption</th><th>Categories</th><th></th></tr>
          </thead>
          <tbody>
            {rows.map((row, i) => (
              <Fragment key={row.id}>
                <tr>
                  <td><img className="thumb-cell" src={'/' + row.image_path} alt="" /></td>
                  <td>{row.caption || '—'}</td>
                  <td>{row.categories || '—'}</td>
                  <td className="row-actions">
                    <button onClick={() => move(row.id, 'up')} disabled={i === 0} title="Move up">↑</button>
                    <button onClick={() => move(row.id, 'down')} disabled={i === rows.length - 1} title="Move down">↓</button>
                    <button onClick={() => startEdit(row)}>Edit</button>
                    <button onClick={() => remove(row.id)} className="btn-danger">Delete</button>
                  </td>
                </tr>
              </Fragment>
            ))}
          </tbody>
        </table>
      )}
    </div>
  );
}
