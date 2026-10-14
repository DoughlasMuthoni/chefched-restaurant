import { Fragment, useEffect, useState } from 'react';
import { api } from '../api';
import ImageUploadField from '../components/ImageUploadField';

const BLANK = { eyebrow: '', title: '', description: '', sub_line: '', cta_label: '', cta_href: 'reservations', image_path: '' };

export default function ServicesManager() {
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
      setRows((await api.getServices()).data);
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
    if (!draft.title.trim()) {
      setError('Title is required.');
      return;
    }
    setSaving(true);
    setError('');
    try {
      if (editingId === 'new') {
        await api.createService(draft);
      } else {
        await api.updateService(editingId, draft);
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
    if (!window.confirm('Delete this service? This cannot be undone.')) return;
    try {
      await api.deleteService(id);
      await load();
    } catch (err) {
      setError(err.message);
    }
  }

  async function move(id, direction) {
    try {
      await api.moveService(id, direction);
      await load();
    } catch (err) {
      setError(err.message);
    }
  }

  return (
    <div className="inbox">
      <div className="inbox-toolbar">
        <h2 className="panel-heading">Services</h2>
        <button className="btn-primary" onClick={startNew}>+ Add Service</button>
      </div>

      {error && <div className="alert alert-error">{error}</div>}

      {editingId !== null && (
        <div className="edit-panel">
          <h3>{editingId === 'new' ? 'New Service' : 'Edit Service'}</h3>
          <label>Eyebrow<input value={draft.eyebrow || ''} onChange={(e) => setDraft({ ...draft, eyebrow: e.target.value })} /></label>
          <label>Title*<input value={draft.title || ''} onChange={(e) => setDraft({ ...draft, title: e.target.value })} /></label>
          <label>Description<textarea rows={4} value={draft.description || ''} onChange={(e) => setDraft({ ...draft, description: e.target.value })} /></label>
          <label>Sub-line (e.g. "Perfect for: ...")<input value={draft.sub_line || ''} onChange={(e) => setDraft({ ...draft, sub_line: e.target.value })} /></label>
          <label>Button Label<input value={draft.cta_label || ''} onChange={(e) => setDraft({ ...draft, cta_label: e.target.value })} /></label>
          <label>Button Link<input value={draft.cta_href || ''} onChange={(e) => setDraft({ ...draft, cta_href: e.target.value })} /></label>
          <ImageUploadField label="Photo" folder="services" value={draft.image_path} onChange={(path) => setDraft({ ...draft, image_path: path })} />
          <div className="edit-panel-actions">
            <button className="btn-primary" onClick={save} disabled={saving}>{saving ? 'Saving…' : 'Save'}</button>
            <button onClick={cancelEdit} disabled={saving}>Cancel</button>
          </div>
        </div>
      )}

      {loading ? (
        <p className="muted">Loading…</p>
      ) : rows.length === 0 ? (
        <p className="muted">No services yet.</p>
      ) : (
        <table className="data-table">
          <thead>
            <tr><th>Title</th><th>Eyebrow</th><th>Button</th><th></th></tr>
          </thead>
          <tbody>
            {rows.map((row, i) => (
              <Fragment key={row.id}>
                <tr>
                  <td>{row.title}</td>
                  <td>{row.eyebrow || '—'}</td>
                  <td>{row.cta_label || '—'}</td>
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
