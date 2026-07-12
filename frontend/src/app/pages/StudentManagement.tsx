import { useState, useEffect } from "react";
import { Search, Plus, MoreHorizontal, Mail, Phone, ChevronUp, ChevronDown, Download, X, User, Edit2, Trash2 } from "lucide-react";
import { motion, AnimatePresence } from "motion/react";
import { api } from "../../lib/api";

type Student = {
  StudentID: number; StudentName: string; ClassID: string; UserID: number;
  fullname?: string; email?: string; phone?: string;
  ClassName?: string;
};

export function StudentManagement() {
  const [students, setStudents] = useState<Student[]>([]);
  const [search, setSearch] = useState("");
  const [loading, setLoading] = useState(true);
  const [selectedStudent, setSelectedStudent] = useState<Student | null>(null);
  const [showAddModal, setShowAddModal] = useState(false);
  const [sortField, setSortField] = useState<string>("StudentName");
  const [sortDir, setSortDir] = useState<"asc" | "desc">("asc");
  const [formData, setFormData] = useState({ StudentName: "", ClassID: "" });
  const [editingId, setEditingId] = useState<number | null>(null);
  const [saving, setSaving] = useState(false);

  const loadStudents = () => {
    setLoading(true);
    api.students.list().then(setStudents).catch(() => {}).finally(() => setLoading(false));
  };

  useEffect(() => { loadStudents(); }, []);

  const filtered = students
    .filter((s) => {
      const q = search.toLowerCase();
      return s.StudentName?.toLowerCase().includes(q) || String(s.StudentID).includes(q);
    })
    .sort((a, b) => {
      const av = String(a[sortField as keyof Student] || "");
      const bv = String(b[sortField as keyof Student] || "");
      return sortDir === "asc" ? av.localeCompare(bv) : bv.localeCompare(av);
    });

  const toggleSort = (field: string) => {
    if (sortField === field) setSortDir(sortDir === "asc" ? "desc" : "asc");
    else { setSortField(field); setSortDir("asc"); }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!formData.StudentName) return;
    setSaving(true);
    try {
      if (editingId) {
        await api.students.update(editingId, formData);
      } else {
        await api.students.create(formData);
      }
      setShowAddModal(false);
      setEditingId(null);
      setFormData({ StudentName: "", ClassID: "" });
      loadStudents();
    } catch (err: any) {
      alert(err.message);
    } finally {
      setSaving(false);
    }
  };

  const handleDelete = async (id: number) => {
    if (!confirm("Are you sure you want to delete this student?")) return;
    try {
      await api.students.delete(id);
      loadStudents();
      setSelectedStudent(null);
    } catch (err: any) {
      alert(err.message);
    }
  };

  const openEdit = (student: Student) => {
    setFormData({ StudentName: student.StudentName, ClassID: student.ClassID || "" });
    setEditingId(student.StudentID);
    setShowAddModal(true);
  };

  const openAdd = () => {
    setFormData({ StudentName: "", ClassID: "" });
    setEditingId(null);
    setShowAddModal(true);
  };

  return (
    <div style={{ fontFamily: "'DM Sans', sans-serif" }}>
      <div className="flex flex-wrap items-center gap-3 mb-5">
        <div className="flex items-center gap-2 px-3 py-2.5 rounded-xl bg-white border flex-1 min-w-48" style={{ borderColor: "rgba(13,31,60,0.1)" }}>
          <Search size={15} style={{ color: "var(--muted-foreground)" }} />
          <input type="text" placeholder="Search by name..." value={search} onChange={(e) => setSearch(e.target.value)}
            className="bg-transparent outline-none flex-1" style={{ fontSize: 13, color: "var(--navy)" }} />
          {search && <button onClick={() => setSearch("")}><X size={14} style={{ color: "var(--muted-foreground)" }} /></button>}
        </div>
        <button onClick={openAdd}
          className="flex items-center gap-2 px-4 py-2.5 rounded-xl transition-all hover:opacity-90"
          style={{ background: "var(--navy)", color: "#fff", fontSize: 13, fontWeight: 500 }}>
          <Plus size={15} /> Add Student
        </button>
        <button className="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border transition-all hover:bg-gray-50"
          style={{ fontSize: 13, color: "var(--navy)", borderColor: "rgba(13,31,60,0.1)" }}>
          <Download size={15} /> Export
        </button>
      </div>

      <div className="flex items-center gap-3 mb-4 flex-wrap">
        <span style={{ fontSize: 13, color: "var(--muted-foreground)" }}>{filtered.length} students</span>
      </div>

      <div className="bg-white rounded-2xl border overflow-hidden" style={{ borderColor: "rgba(13,31,60,0.08)" }}>
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead>
              <tr style={{ borderBottom: "1px solid rgba(13,31,60,0.07)" }}>
                {[{ label: "Name", field: "StudentName" }, { label: "Student ID", field: "StudentID" }, { label: "Email", field: "email" }, { label: "Phone", field: "phone" }, { label: "Class", field: "ClassName" }, { label: "Actions", field: null }].map((col) => (
                  <th key={col.label} className={`px-4 py-3 text-left ${col.field ? "cursor-pointer hover:bg-gray-50 select-none" : ""}`}
                    onClick={() => col.field && toggleSort(col.field)} style={{ fontSize: 11, fontWeight: 600, color: "var(--muted-foreground)", letterSpacing: "0.05em", textTransform: "uppercase" }}>
                    <span className="flex items-center gap-1">{col.label}{col.field && sortField === col.field && (sortDir === "asc" ? <ChevronUp size={11} /> : <ChevronDown size={11} />)}</span>
                  </th>
                ))}
              </tr>
            </thead>
            <tbody>
              {loading ? (
                <tr><td colSpan={6} className="text-center py-12"><div className="w-6 h-6 border-2 border-navy/30 border-t-navy rounded-full animate-spin mx-auto" /></td></tr>
              ) : filtered.map((student, i) => (
                <motion.tr key={student.StudentID} initial={{ opacity: 0 }} animate={{ opacity: 1 }} transition={{ delay: i * 0.03 }}
                  className="border-b last:border-b-0 hover:bg-blue-50/30 cursor-pointer transition-colors" style={{ borderColor: "rgba(13,31,60,0.05)" }}
                  onClick={() => setSelectedStudent(student)}>
                  <td className="px-4 py-3">
                    <div className="flex items-center gap-3">
                      <div className="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                        style={{ background: `hsl(${student.StudentID * 37}, 60%, 90%)`, color: `hsl(${student.StudentID * 37}, 60%, 35%)`, fontSize: 12, fontWeight: 700 }}>
                        {student.StudentName?.split(" ").map((n) => n[0]).join("").slice(0, 2)}
                      </div>
                      <div style={{ fontSize: 14, fontWeight: 500, color: "var(--navy)" }}>{student.StudentName}</div>
                    </div>
                  </td>
                  <td className="px-4 py-3" style={{ fontSize: 13, color: "var(--navy)", fontFamily: "'DM Mono', monospace" }}>{student.StudentID}</td>
                  <td className="px-4 py-3" style={{ fontSize: 13, color: "var(--navy)" }}>{student.email || "-"}</td>
                  <td className="px-4 py-3" style={{ fontSize: 13, color: "var(--navy)" }}>{student.phone || "-"}</td>
                  <td className="px-4 py-3" style={{ fontSize: 13, color: "var(--navy)" }}>{student.ClassName || "-"}</td>
                  <td className="px-4 py-3">
                    <div className="flex items-center gap-1">
                      <button onClick={(e) => { e.stopPropagation(); openEdit(student); }}
                        className="p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                        <Edit2 size={13} style={{ color: "var(--muted-foreground)" }} />
                      </button>
                      <button onClick={(e) => { e.stopPropagation(); handleDelete(student.StudentID); }}
                        className="p-1.5 rounded-lg hover:bg-red-50 transition-colors">
                        <Trash2 size={13} style={{ color: "#d4183d" }} />
                      </button>
                    </div>
                  </td>
                </motion.tr>
              ))}
            </tbody>
          </table>
        </div>
        {!loading && filtered.length === 0 && (
          <div className="flex flex-col items-center justify-center py-16">
            <User size={36} style={{ color: "var(--muted-foreground)", opacity: 0.4 }} />
            <p style={{ fontSize: 15, color: "var(--muted-foreground)", marginTop: 12 }}>No students found</p>
          </div>
        )}
      </div>

      {/* Detail Modal */}
      <AnimatePresence>
        {selectedStudent && (
          <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} exit={{ opacity: 0 }}
            className="fixed inset-0 z-50 flex items-center justify-center p-4"
            style={{ background: "rgba(0,0,0,0.4)", backdropFilter: "blur(4px)" }}
            onClick={() => setSelectedStudent(null)}>
            <motion.div initial={{ scale: 0.95, opacity: 0 }} animate={{ scale: 1, opacity: 1 }} exit={{ scale: 0.95, opacity: 0 }}
              className="bg-white rounded-2xl w-full max-w-lg overflow-hidden" style={{ boxShadow: "0 24px 64px rgba(0,0,0,0.15)" }}
              onClick={(e) => e.stopPropagation()}>
              <div className="p-6" style={{ background: "var(--navy)" }}>
                <div className="flex items-center justify-between mb-4">
                  <div className="w-12 h-12 rounded-2xl flex items-center justify-center"
                    style={{ background: "var(--gold)", fontFamily: "'Outfit', sans-serif", fontWeight: 700, fontSize: 16, color: "var(--navy)" }}>
                    {selectedStudent.StudentName?.split(" ").map((n) => n[0]).join("").slice(0, 2)}
                  </div>
                  <button onClick={() => setSelectedStudent(null)} className="text-white/50 hover:text-white transition-colors"><X size={20} /></button>
                </div>
                <h2 style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 700, fontSize: 22, color: "#fff" }}>{selectedStudent.StudentName}</h2>
                <p style={{ fontSize: 13, color: "rgba(255,255,255,0.55)", marginTop: 4 }}>ID: {selectedStudent.StudentID}</p>
              </div>
              <div className="p-6 space-y-4">
                {selectedStudent.email && (
                  <div className="flex items-center gap-3"><Mail size={15} style={{ color: "var(--muted-foreground)" }} /><span style={{ fontSize: 13, color: "var(--navy)" }}>{selectedStudent.email}</span></div>
                )}
                {selectedStudent.phone && (
                  <div className="flex items-center gap-3"><Phone size={15} style={{ color: "var(--muted-foreground)" }} /><span style={{ fontSize: 13, color: "var(--navy)" }}>{selectedStudent.phone}</span></div>
                )}
                <div className="flex gap-3 pt-2">
                  <button onClick={() => { setSelectedStudent(null); openEdit(selectedStudent); }}
                    className="flex-1 py-2.5 rounded-xl transition-all hover:opacity-90"
                    style={{ background: "var(--navy)", color: "#fff", fontSize: 13, fontWeight: 500 }}>Edit Student</button>
                  <button onClick={() => handleDelete(selectedStudent.StudentID)}
                    className="flex-1 py-2.5 rounded-xl border transition-all hover:bg-red-50"
                    style={{ fontSize: 13, fontWeight: 500, color: "#d4183d", borderColor: "#fecdd3" }}>Delete</button>
                </div>
              </div>
            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>

      {/* Add/Edit Modal */}
      <AnimatePresence>
        {showAddModal && (
          <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} exit={{ opacity: 0 }}
            className="fixed inset-0 z-50 flex items-center justify-center p-4"
            style={{ background: "rgba(0,0,0,0.4)", backdropFilter: "blur(4px)" }}
            onClick={() => { setShowAddModal(false); setEditingId(null); }}>
            <motion.div initial={{ scale: 0.95, opacity: 0 }} animate={{ scale: 1, opacity: 1 }} exit={{ scale: 0.95 }}
              className="bg-white rounded-2xl w-full max-w-md overflow-hidden" style={{ boxShadow: "0 24px 64px rgba(0,0,0,0.15)" }}
              onClick={(e) => e.stopPropagation()}>
              <div className="flex items-center justify-between p-6 border-b" style={{ borderColor: "rgba(13,31,60,0.08)" }}>
                <h3 style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 700, fontSize: 18, color: "var(--navy)" }}>{editingId ? "Edit Student" : "Add New Student"}</h3>
                <button onClick={() => { setShowAddModal(false); setEditingId(null); }}><X size={18} style={{ color: "var(--muted-foreground)" }} /></button>
              </div>
              <form onSubmit={handleSubmit} className="p-6 space-y-4">
                <div>
                  <label style={{ fontSize: 13, fontWeight: 500, color: "var(--navy)", display: "block", marginBottom: 6 }}>Student Name</label>
                  <input type="text" placeholder="Enter student name" value={formData.StudentName}
                    onChange={(e) => setFormData((p) => ({ ...p, StudentName: e.target.value }))}
                    className="w-full px-4 py-2.5 rounded-xl outline-none"
                    style={{ background: "var(--input-background)", fontSize: 13, color: "var(--navy)", border: "1.5px solid transparent" }}
                    onFocus={(e) => (e.target.style.border = "1.5px solid var(--gold)")}
                    onBlur={(e) => (e.target.style.border = "1.5px solid transparent")}
                    required />
                </div>
                <div>
                  <label style={{ fontSize: 13, fontWeight: 500, color: "var(--navy)", display: "block", marginBottom: 6 }}>Class ID</label>
                  <input type="text" placeholder="Optional class ID" value={formData.ClassID}
                    onChange={(e) => setFormData((p) => ({ ...p, ClassID: e.target.value }))}
                    className="w-full px-4 py-2.5 rounded-xl outline-none"
                    style={{ background: "var(--input-background)", fontSize: 13, color: "var(--navy)", border: "1.5px solid transparent" }}
                    onFocus={(e) => (e.target.style.border = "1.5px solid var(--gold)")}
                    onBlur={(e) => (e.target.style.border = "1.5px solid transparent")} />
                </div>
                <button type="submit" disabled={saving}
                  className="w-full py-3 rounded-xl transition-all hover:opacity-90 flex items-center justify-center gap-2"
                  style={{ background: "var(--navy)", color: "#fff", fontSize: 14, fontWeight: 600 }}>
                  {saving ? <div className="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin" /> : editingId ? "Update Student" : "Create Student"}
                </button>
              </form>
            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
}
