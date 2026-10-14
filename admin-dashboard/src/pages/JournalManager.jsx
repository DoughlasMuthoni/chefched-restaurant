import { Fragment, useEffect, useState } from 'react';
import { api } from '../api';
import ImageUploadField from '../components/ImageUploadField';

function today() {
  return new Date().toISOString().slice(0, 10);
}

const BLANK = { title: '', excerpt: '', body: '', category: '', cover_image_path: '', published_at: today() };

export default function JournalManager() {
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
      setRows((await api.getPosts()).data);
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
        await api.createPost(draft);
      } else {
        await api.updatePost(editingId, draft);
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
    if (!window.confirm('Delete this journal entry? This cannot be undone.')) return;
    try {
      await api.deletePost(id);
      await load();
    } catch (err) {
      setError(err.message);
    }
  }

  return (
    <div className="inbox">
      <div className="inbox-toolbar">
        <h2 className="panel-heading">Journal</h2>
        <button className="btn-primary" onClick={startNew}>+ New Entry</button>
      </div>

      {error && <div className="alert alert-error">{error}</div>}

      {editingId !== null && (
        <div className="edit-panel">
          <h3>{editingId === 'new' ? 'New Journal Entry' : 'Edit Journal Entry'}</h3>
          <label>Title*<input value={draft.title || ''} onChange={(e) => setDraft({ ...draft, title: e.target.value })} /></label>
          <label>Category<input value={draft.category || ''} onChange={(e) => setDraft({ ...draft, category: e.target.value })} /></label>
          <label>Published Date<input type="date" value={draft.published_at || today()} onChange={(e) => setDraft({ ...draft, published_at: e.target.value })} /></label>
          <label>Excerpt (shown on the journal listing page)<textarea rows={2} value={draft.excerpt || ''} onChange={(e) => setDraft({ ...draft, excerpt: e.target.value })} /></label>
          <label>
            Body (basic HTML allowed — e.g. &lt;p&gt;, &lt;blockquote&gt;, &lt;em&gt;)
            <textarea rows={10} value={draft.body || ''} onChange={(e) => setDraft({ ...draft, body: e.target.value })} />
          </label>
          <ImageUploadField label="Cover Image" folder="blog" value={draft.cover_image_path} onChange={(path) => setDraft({ ...draft, cover_image_path: path })} />
          {editingId !== 'new' && draft.slug && (
            <p className="muted small">Live at: <a href={'/blog/' + draft.slug} target="_blank" rel="noreferrer">/blog/{draft.slug}</a></p>
          )}
          <div className="edit-panel-actions">
            <button className="btn-primary" onClick={save} disabled={saving}>{saving ? 'Saving…' : 'Save'}</button>
            <button onClick={cancelEdit} disabled={saving}>Cancel</button>
          </div>
        </div>
      )}

      {loading ? (
        <p className="muted">Loading…</p>
      ) : rows.length === 0 ? (
        <p className="muted">No journal entries yet.</p>
      ) : (
        <table className="data-table">
          <thead>
            <tr><th>Title</th><th>Category</th><th>Published</th><th></th></tr>
          </thead>
          <tbody>
            {rows.map((row) => (
              <Fragment key={row.id}>
                <tr>
                  <td>{row.title}</td>
                  <td>{row.category || '—'}</td>
                  <td>{row.published_at || '—'}</td>
                  <td className="row-actions">
                    <a href={'/blog/' + row.slug} target="_blank" rel="noreferrer">View</a>
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
