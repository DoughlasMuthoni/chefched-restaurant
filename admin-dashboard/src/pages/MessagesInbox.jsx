import { Fragment, useEffect, useState } from 'react';
import { api } from '../api';

const STATUSES = ['new', 'read', 'responded'];

function waLink(phone, name) {
  const digits = (phone || '').replace(/[^\d]/g, '');
  const text = encodeURIComponent(`Hi ${name}, thanks for reaching out to Njogu Nderitu! `);
  return `https://wa.me/${digits}?text=${text}`;
}

export default function MessagesInbox() {
  const [rows, setRows] = useState([]);
  const [total, setTotal] = useState(0);
  const [status, setStatus] = useState('all');
  const [search, setSearch] = useState('');
  const [page, setPage] = useState(1);
  const [openId, setOpenId] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  async function load() {
    setLoading(true);
    setError('');
    try {
      const res = await api.getMessages({ status, search, page, per_page: 25 });
      setRows(res.data);
      setTotal(res.total);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  }

  useEffect(() => { load(); }, [status, page]); // eslint-disable-line react-hooks/exhaustive-deps

  function handleSearchSubmit(e) {
    e.preventDefault();
    setPage(1);
    load();
  }

  async function changeStatus(id, newStatus) {
    setRows((prev) => prev.map((r) => (r.id === id ? { ...r, status: newStatus } : r)));
    try {
      await api.updateMessageStatus(id, newStatus);
    } catch (err) {
      setError(err.message);
      load();
    }
  }

  return (
    <div className="inbox">
      <div className="inbox-toolbar">
        <div className="status-filters">
          {['all', ...STATUSES].map((s) => (
            <button key={s} className={s === status ? 'active' : ''} onClick={() => { setStatus(s); setPage(1); }}>
              {s === 'all' ? 'All' : s.charAt(0).toUpperCase() + s.slice(1)}
            </button>
          ))}
        </div>
        <form className="search-box" onSubmit={handleSearchSubmit}>
          <input placeholder="Search name, email, phone…" value={search} onChange={(e) => setSearch(e.target.value)} />
          <button type="submit">Search</button>
        </form>
      </div>

      {error && <div className="alert alert-error">{error}</div>}
      {loading ? (
        <p className="muted">Loading…</p>
      ) : rows.length === 0 ? (
        <p className="muted">No messages found.</p>
      ) : (
        <table className="data-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Received</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            {rows.map((row) => (
              <Fragment key={row.id}>
                <tr className="data-row" onClick={() => setOpenId(openId === row.id ? null : row.id)}>
                  <td>{row.name}</td>
                  <td>{row.email}</td>
                  <td>{row.phone || '—'}</td>
                  <td>{new Date(row.created_at).toLocaleString()}</td>
                  <td>
                    <select
                      value={row.status}
                      onClick={(e) => e.stopPropagation()}
                      onChange={(e) => changeStatus(row.id, e.target.value)}
                      className={`status-pill status-${row.status}`}
                    >
                      {STATUSES.map((s) => <option key={s} value={s}>{s}</option>)}
                    </select>
                  </td>
                </tr>
                {openId === row.id && (
                  <tr className="detail-row">
                    <td colSpan={5}>
                      <div className="detail-grid">
                        <div className="detail-full"><strong>Message</strong><br />{row.message}</div>
                        <div className="detail-full">
                          {row.phone && (
                            <a className="wa-btn" href={waLink(row.phone, row.name)} target="_blank" rel="noreferrer">
                              Reply on WhatsApp
                            </a>
                          )}
                          <span className="muted small">
                            {' '}Notifications sent — WhatsApp: {row.whatsapp_sent ? 'yes' : 'no'}, Email: {row.email_sent ? 'yes' : 'no'}
                          </span>
                        </div>
                      </div>
                    </td>
                  </tr>
                )}
              </Fragment>
            ))}
          </tbody>
        </table>
      )}

      <div className="pagination">
        <button disabled={page <= 1} onClick={() => setPage((p) => p - 1)}>Previous</button>
        <span className="muted">Page {page} · {total} total</span>
        <button disabled={page * 25 >= total} onClick={() => setPage((p) => p + 1)}>Next</button>
      </div>
    </div>
  );
}
