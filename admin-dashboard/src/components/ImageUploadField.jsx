import { useState } from 'react';
import { api } from '../api';

/** File input that uploads immediately on selection and reports back the
 *  server-side path (e.g. "uploads/services/abc123.jpg") via onChange — used
 *  by every CMS manager page (services/gallery/journal/homepage settings)
 *  so upload handling is written once. */
export default function ImageUploadField({ label, value, onChange, folder }) {
  const [uploading, setUploading] = useState(false);
  const [error, setError] = useState('');

  async function handleFile(e) {
    const file = e.target.files?.[0];
    if (!file) return;
    setUploading(true);
    setError('');
    try {
      const res = await api.uploadImage(file, folder);
      onChange(res.path);
    } catch (err) {
      setError(err.message);
    } finally {
      setUploading(false);
      e.target.value = '';
    }
  }

  return (
    <div className="image-field">
      {label && <label>{label}</label>}
      {value && (
        <img className="image-field-preview" src={'/' + value} alt="" />
      )}
      <input type="file" accept="image/jpeg,image/png,image/webp" onChange={handleFile} disabled={uploading} />
      {uploading && <span className="muted small"> Uploading…</span>}
      {error && <div className="alert alert-error">{error}</div>}
    </div>
  );
}
