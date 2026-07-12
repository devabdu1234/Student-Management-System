const BASE = '/api';

async function request(path: string, options: RequestInit = {}) {
  const res = await fetch(BASE + path, {
    headers: { 'Content-Type': 'application/json', ...options.headers },
    credentials: 'same-origin',
    ...options,
  });
  const data = await res.json();
  if (!res.ok) throw new Error(data.error || 'Request failed');
  return data;
}

export const api = {
  login: (email: string, password: string) =>
    request('/login', { method: 'POST', body: JSON.stringify({ email, password }) }),

  logout: () => request('/logout', { method: 'POST' }),

  me: () => request('/me'),

  users: {
    list: () => request('/users'),
    get: (id: number) => request(`/users/${id}`),
    create: (data: any) => request('/users', { method: 'POST', body: JSON.stringify(data) }),
    update: (id: number, data: any) => request(`/users/${id}`, { method: 'PUT', body: JSON.stringify(data) }),
    delete: (id: number) => request(`/users/${id}`, { method: 'DELETE' }),
  },

  students: {
    list: () => request('/students'),
    get: (id: number) => request(`/students/${id}`),
    create: (data: any) => request('/students', { method: 'POST', body: JSON.stringify(data) }),
    update: (id: number, data: any) => request(`/students/${id}`, { method: 'PUT', body: JSON.stringify(data) }),
    delete: (id: number) => request(`/students/${id}`, { method: 'DELETE' }),
  },

  subjects: {
    list: () => request('/subjects'),
    get: (id: number) => request(`/subjects/${id}`),
    create: (data: any) => request('/subjects', { method: 'POST', body: JSON.stringify(data) }),
    delete: (id: number) => request(`/subjects/${id}`, { method: 'DELETE' }),
  },

  attendance: {
    list: () => request('/attendance'),
    create: (data: any) => request('/attendance', { method: 'POST', body: JSON.stringify(data) }),
  },

  exams: {
    list: () => request('/exams'),
    create: (data: any) => request('/exams', { method: 'POST', body: JSON.stringify(data) }),
  },

  examresults: {
    list: () => request('/examresults'),
    create: (data: any) => request('/examresults', { method: 'POST', body: JSON.stringify(data) }),
  },

  dashboard: () => request('/dashboard'),

  features: { list: () => request('/features') },

  teachers: {
    list: () => request('/teachers'),
    get: (id: number) => request(`/teachers/${id}`),
    create: (data: any) => request('/teachers', { method: 'POST', body: JSON.stringify(data) }),
    delete: (id: number) => request(`/teachers/${id}`, { method: 'DELETE' }),
  },

  classes: {
    list: () => request('/classes'),
    create: (data: any) => request('/classes', { method: 'POST', body: JSON.stringify(data) }),
  },

  schedules: { list: () => request('/schedules') },
};
