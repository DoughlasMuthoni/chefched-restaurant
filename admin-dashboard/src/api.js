const BASE = '/api';

async function request(path, options = {}) {
  // FormData (file uploads) must keep the browser's own multipart boundary
  // header — never force JSON Content-Type on those requests.
  const isFormData = options.body instanceof FormData;
  const res = await fetch(BASE + path, {
    credentials: 'include',
    headers: isFormData ? { ...(options.headers || {}) } : { 'Content-Type': 'application/json', ...(options.headers || {}) },
    ...options,
  });

  let body = null;
  try {
    body = await res.json();
  } catch (e) {
    // non-JSON response (shouldn't happen, but don't crash the UI over it)
  }

  if (!res.ok) {
    const message = body?.message || `Request failed (${res.status})`;
    const err = new Error(message);
    err.status = res.status;
    err.body = body;
    throw err;
  }
  return body;
}

export const api = {
  login: (username, password) =>
    request('/admin/login.php', { method: 'POST', body: JSON.stringify({ username, password }) }),
  logout: () => request('/admin/logout.php', { method: 'POST' }),
  me: () => request('/admin/me.php'),

  getEnquiries: (params = {}) =>
    request('/admin/enquiries.php?' + new URLSearchParams(params).toString()),
  updateEnquiryStatus: (id, status) =>
    request(`/admin/enquiries.php?id=${id}`, { method: 'PATCH', body: JSON.stringify({ status }) }),

  getMessages: (params = {}) =>
    request('/admin/messages.php?' + new URLSearchParams(params).toString()),
  updateMessageStatus: (id, status) =>
    request(`/admin/messages.php?id=${id}`, { method: 'PATCH', body: JSON.stringify({ status }) }),

  getServices: () => request('/admin/services.php'),
  createService: (data) => request('/admin/services.php', { method: 'POST', body: JSON.stringify(data) }),
  updateService: (id, data) => request(`/admin/services.php?id=${id}`, { method: 'PATCH', body: JSON.stringify(data) }),
  moveService: (id, direction) => request(`/admin/services.php?id=${id}`, { method: 'PATCH', body: JSON.stringify({ move: direction }) }),
  deleteService: (id) => request(`/admin/services.php?id=${id}`, { method: 'DELETE' }),

  getGalleryItems: () => request('/admin/gallery.php'),
  createGalleryItem: (data) => request('/admin/gallery.php', { method: 'POST', body: JSON.stringify(data) }),
  updateGalleryItem: (id, data) => request(`/admin/gallery.php?id=${id}`, { method: 'PATCH', body: JSON.stringify(data) }),
  moveGalleryItem: (id, direction) => request(`/admin/gallery.php?id=${id}`, { method: 'PATCH', body: JSON.stringify({ move: direction }) }),
  deleteGalleryItem: (id) => request(`/admin/gallery.php?id=${id}`, { method: 'DELETE' }),

  getPosts: () => request('/admin/posts.php'),
  createPost: (data) => request('/admin/posts.php', { method: 'POST', body: JSON.stringify(data) }),
  updatePost: (id, data) => request(`/admin/posts.php?id=${id}`, { method: 'PATCH', body: JSON.stringify(data) }),
  deletePost: (id) => request(`/admin/posts.php?id=${id}`, { method: 'DELETE' }),

  getSettings: () => request('/admin/settings.php'),
  updateSettings: (key, value) => request(`/admin/settings.php?key=${key}`, { method: 'PATCH', body: JSON.stringify({ value }) }),

  uploadImage: (file, folder) => {
    const body = new FormData();
    body.append('image', file);
    body.append('folder', folder);
    return request('/admin/upload.php', { method: 'POST', body });
  },
};
