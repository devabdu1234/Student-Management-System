import { useState, useEffect } from "react";
import { Plus, Search, Edit2, Trash2, Shield, User, UserCog, Check, X } from "lucide-react";
import { motion, AnimatePresence } from "motion/react";
import { api } from "../../lib/api";

type SystemUser = {
  user_id: number; fullname: string; email: string; role: string;
  phone: string; created_at: string;
};

const roleConfig: Record<string, { label: string; color: string; bg: string }> = {
  admin: { label: "Administrator", color: "#8b1a1a", bg: "#fff0f0" },
  lecturer: { label: "Lecturer", color: "#0d1f3c", bg: "#e8edf5" },
  student: { label: "Student", color: "#16a34a", bg: "#f0fdf4" },
  parent: { label: "Parent", color: "#2563eb", bg: "#eff6ff" },
};

export function UserManagement() {
  const [users, setUsers] = useState<SystemUser[]>([]);
  const [search, setSearch] = useState("");
  const [loading, setLoading] = useState(true);
  const [showAdd, setShowAdd] = useState(false);
  const [editing, setEditing] = useState<SystemUser | null>(null);
  const [form, setForm] = useState({ fullname: "", email: "", role: "lecturer", phone: "", password: "password123" });
  const [saving, setSaving] = useState(false);

  const load = () => {
    setLoading(true);
    api.users.list().then(setUsers).catch(() => {}).finally(() => setLoading(false));
  };

  useEffect(() => { load(); }, []);

  const filtered = users.filter((u) =>
    u.fullname?.toLowerCase().includes(search.toLowerCase()) || u.email?.toLowerCase().includes(search.toLowerCase())
  );

  const openAdd = () => {
    setEditing(null);
    setForm({ fullname: "", email: "", role: "lecturer", phone: "", password: "password123" });
    setShowAdd(true);
  };

  const openEdit = (u: SystemUser) => {
    setEditing(u);
    setForm({ fullname: u.fullname, email: u.email, role: u.role, phone: u.phone, password: "" });
    setShowAdd(true);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!form.fullname || !form.email) return;
    setSaving(true);
    try {
      if (editing) {
        await api.users.update(editing.user_id, { fullname: form.fullname, email: form.email, role: form.role, phone: form.phone });
      } else {
        await api.users.create(form);
      }
      setShowAdd(false);
      load();
    } catch (err: any) {
      alert(err.message);
    } finally {
      setSaving(false);
    }
  };

  const handleDelete = async (id: number) => {
    if (!confirm("Delete this user?")) return;
    try { await api.users.delete(id); load(); } catch (err: any) { alert(err.message); }
  };

  return (
    <div style={{ fontFamily: "'DM Sans', sans-serif" }}>
      <div className="grid grid-cols-4 gap-4 mb-5">
        {[
          { label: "Total Users", value: users.length },
          { label: "Admins", value: users.filter((u) => u.role === "admin").length },
          { label: "Lecturers", value: users.filter((u) => u.role === "lecturer").length },
          { label: "Students", value: users.filter((u) => u.role === "student").length },
        ].map((stat) => (
          <div key={stat.label} className="bg-white rounded-xl p-4 border" style={{ borderColor: "rgba(13,31,60,0.07)" }}>
            <div style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 700, fontSize: 28, color: "var(--navy)" }}>{stat.value}</div>
            <div style={{ fontSize: 12, color: "var(--muted-foreground)" }}>{stat.label}</div>
          </div>
        ))}
      </div>

      <div className="flex items-center gap-3 mb-5 flex-wrap">
        <div className="flex items-center gap-2 px-3 py-2.5 rounded-xl bg-white border flex-1 min-w-48" style={{ borderColor: "rgba(13,31,60,0.1)" }}>
          <Search size={15} style={{ color: "var(--muted-foreground)" }} />
          <input type="text" placeholder="Search users..." value={search} onChange={(e) => setSearch(e.target.value)}
            className="bg-transparent outline-none flex-1" style={{ fontSize: 13, color: "var(--navy)" }} />
        </div>
        <button onClick={openAdd}
          className="flex items-center gap-2 px-4 py-2.5 rounded-xl hover:opacity-90 transition-all"
          style={{ background: "var(--navy)", color: "#fff", fontSize: 13, fontWeight: 500 }}>
          <Plus size={15} /> Add User
        </button>
      </div>

      <div className="bg-white rounded-2xl border overflow-hidden" style={{ borderColor: "rgba(13,31,60,0.08)" }}>
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead>
              <tr style={{ borderBottom: "1px solid rgba(13,31,60,0.07)" }}>
                {["User", "Role", "Phone", "Created", "Actions"].map((h) => (
                  <th key={h} className="px-4 py-3 text-left" style={{ fontSize: 11, fontWeight: 600, color: "var(--muted-foreground)", letterSpacing: "0.05em", textTransform: "uppercase" }}>{h}</th>
                ))}
              </tr>
            </thead>
            <tbody>
              {loading ? (
                <tr><td colSpan={5} className="text-center py-12"><div className="w-6 h-6 border-2 border-navy/30 border-t-navy rounded-full animate-spin mx-auto" /></td></tr>
              ) : filtered.map((user, i) => {
                const rc = roleConfig[user.role] || { label: user.role, color: "#6b7280", bg: "#f3f4f6" };
                return (
                  <motion.tr key={user.user_id} initial={{ opacity: 0 }} animate={{ opacity: 1 }} transition={{ delay: i * 0.04 }}
                    className="border-b last:border-0 hover:bg-blue-50/30 transition-colors" style={{ borderColor: "rgba(13,31,60,0.05)" }}>
                    <td className="px-4 py-3">
                      <div className="flex items-center gap-3">
                        <div className="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                          style={{ background: `hsl(${user.user_id * 53}, 55%, 88%)`, color: `hsl(${user.user_id * 53}, 55%, 35%)`, fontSize: 12, fontWeight: 700 }}>
                          {user.fullname?.split(" ").map((n) => n[0]).join("").slice(0, 2)}
                        </div>
                        <div>
                          <div style={{ fontSize: 14, fontWeight: 500, color: "var(--navy)" }}>{user.fullname}</div>
                          <div style={{ fontSize: 11, color: "var(--muted-foreground)" }}>{user.email}</div>
                        </div>
                      </div>
                    </td>
                    <td className="px-4 py-3">
                      <span className="flex items-center gap-1.5 px-2.5 py-1 rounded-full w-fit"
                        style={{ background: rc.bg, color: rc.color, fontSize: 11, fontWeight: 600 }}>
                        {rc.label}
                      </span>
                    </td>
                    <td className="px-4 py-3" style={{ fontSize: 13, color: "var(--navy)" }}>{user.phone || "-"}</td>
                    <td className="px-4 py-3" style={{ fontSize: 12, color: "var(--muted-foreground)" }}>{user.created_at?.slice(0, 10) || "-"}</td>
                    <td className="px-4 py-3">
                      <div className="flex items-center gap-1">
                        <button onClick={() => openEdit(user)} className="p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                          <Edit2 size={13} style={{ color: "var(--muted-foreground)" }} />
                        </button>
                        <button onClick={() => handleDelete(user.user_id)} className="p-1.5 rounded-lg hover:bg-red-50 transition-colors">
                          <Trash2 size={13} style={{ color: "#d4183d" }} />
                        </button>
                      </div>
                    </td>
                  </motion.tr>
                );
              })}
            </tbody>
          </table>
        </div>
      </div>

      <AnimatePresence>
        {showAdd && (
          <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} exit={{ opacity: 0 }}
            className="fixed inset-0 z-50 flex items-center justify-center p-4"
            style={{ background: "rgba(0,0,0,0.4)", backdropFilter: "blur(4px)" }}
            onClick={() => setShowAdd(false)}>
            <motion.div initial={{ scale: 0.95, opacity: 0 }} animate={{ scale: 1, opacity: 1 }} exit={{ scale: 0.95 }}
              className="bg-white rounded-2xl w-full max-w-md overflow-hidden" style={{ boxShadow: "0 24px 64px rgba(0,0,0,0.15)" }}
              onClick={(e) => e.stopPropagation()}>
              <div className="flex items-center justify-between p-6 border-b" style={{ borderColor: "rgba(13,31,60,0.08)" }}>
                <h3 style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 700, fontSize: 18, color: "var(--navy)" }}>{editing ? "Edit User" : "Add New User"}</h3>
                <button onClick={() => setShowAdd(false)}><X size={18} style={{ color: "var(--muted-foreground)" }} /></button>
              </div>
              <form onSubmit={handleSubmit} className="p-6 space-y-4">
                <div>
                  <label style={{ fontSize: 13, fontWeight: 500, color: "var(--navy)", display: "block", marginBottom: 6 }}>Full Name</label>
                  <input type="text" placeholder="Full name" value={form.fullname}
                    onChange={(e) => setForm((p) => ({ ...p, fullname: e.target.value }))}
                    className="w-full px-4 py-2.5 rounded-xl outline-none"
                    style={{ background: "var(--input-background)", fontSize: 13, color: "var(--navy)", border: "1.5px solid transparent" }}
                    onFocus={(e) => (e.target.style.border = "1.5px solid var(--gold)")}
                    onBlur={(e) => (e.target.style.border = "1.5px solid transparent")} required />
                </div>
                <div>
                  <label style={{ fontSize: 13, fontWeight: 500, color: "var(--navy)", display: "block", marginBottom: 6 }}>Email</label>
                  <input type="email" placeholder="email@icst.edu.ph" value={form.email}
                    onChange={(e) => setForm((p) => ({ ...p, email: e.target.value }))}
                    className="w-full px-4 py-2.5 rounded-xl outline-none"
                    style={{ background: "var(--input-background)", fontSize: 13, color: "var(--navy)", border: "1.5px solid transparent" }}
                    onFocus={(e) => (e.target.style.border = "1.5px solid var(--gold)")}
                    onBlur={(e) => (e.target.style.border = "1.5px solid transparent")} required />
                </div>
                <div>
                  <label style={{ fontSize: 13, fontWeight: 500, color: "var(--navy)", display: "block", marginBottom: 6 }}>Phone</label>
                  <input type="text" placeholder="+63..." value={form.phone}
                    onChange={(e) => setForm((p) => ({ ...p, phone: e.target.value }))}
                    className="w-full px-4 py-2.5 rounded-xl outline-none"
                    style={{ background: "var(--input-background)", fontSize: 13, color: "var(--navy)", border: "1.5px solid transparent" }}
                    onFocus={(e) => (e.target.style.border = "1.5px solid var(--gold)")}
                    onBlur={(e) => (e.target.style.border = "1.5px solid transparent")} />
                </div>
                <div>
                  <label style={{ fontSize: 13, fontWeight: 500, color: "var(--navy)", display: "block", marginBottom: 6 }}>Role</label>
                  <select value={form.role} onChange={(e) => setForm((p) => ({ ...p, role: e.target.value }))}
                    className="w-full px-4 py-2.5 rounded-xl outline-none cursor-pointer"
                    style={{ background: "var(--input-background)", fontSize: 13, color: "var(--navy)", border: "1.5px solid transparent" }}>
                    <option value="lecturer">Lecturer</option>
                    <option value="admin">Administrator</option>
                    <option value="student">Student</option>
                    <option value="parent">Parent</option>
                  </select>
                </div>
                {!editing && (
                  <div>
                    <label style={{ fontSize: 13, fontWeight: 500, color: "var(--navy)", display: "block", marginBottom: 6 }}>Password</label>
                    <input type="password" value={form.password}
                      onChange={(e) => setForm((p) => ({ ...p, password: e.target.value }))}
                      className="w-full px-4 py-2.5 rounded-xl outline-none"
                      style={{ background: "var(--input-background)", fontSize: 13, color: "var(--navy)", border: "1.5px solid transparent" }}
                      onFocus={(e) => (e.target.style.border = "1.5px solid var(--gold)")}
                      onBlur={(e) => (e.target.style.border = "1.5px solid transparent")} required />
                  </div>
                )}
                <button type="submit" disabled={saving}
                  className="w-full py-3 rounded-xl transition-all hover:opacity-90 flex items-center justify-center gap-2"
                  style={{ background: saving ? "var(--gold)" : "var(--navy)", color: "#fff", fontSize: 14, fontWeight: 600 }}>
                  {saving ? <div className="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin" /> : editing ? "Update User" : "Create User Account"}
                </button>
              </form>
            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
}
