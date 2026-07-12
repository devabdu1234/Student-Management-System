import { useState, useEffect } from "react";
import { Plus, Search, BookOpen, Users, Clock, MapPin, ChevronRight, Edit2, Trash2, X } from "lucide-react";
import { motion, AnimatePresence } from "motion/react";
import { api } from "../../lib/api";

type Subject = {
  SubjectID: number; SubjectName: string;
};

export function SubjectManagement() {
  const [subjects, setSubjects] = useState<Subject[]>([]);
  const [search, setSearch] = useState("");
  const [loading, setLoading] = useState(true);
  const [selected, setSelected] = useState<Subject | null>(null);
  const [showAdd, setShowAdd] = useState(false);
  const [newName, setNewName] = useState("");
  const [saving, setSaving] = useState(false);

  const load = () => {
    setLoading(true);
    api.subjects.list().then(setSubjects).catch(() => {}).finally(() => setLoading(false));
  };

  useEffect(() => { load(); }, []);

  const filtered = subjects.filter(
    (s) => s.SubjectName?.toLowerCase().includes(search.toLowerCase())
  );

  const handleAdd = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!newName) return;
    setSaving(true);
    try {
      await api.subjects.create({ SubjectName: newName });
      setShowAdd(false);
      setNewName("");
      load();
    } catch (err: any) { alert(err.message); }
    finally { setSaving(false); }
  };

  const handleDelete = async (id: number) => {
    if (!confirm("Delete this subject?")) return;
    try {
      await api.subjects.delete(id);
      load();
      setSelected(null);
    } catch (err: any) { alert(err.message); }
  };

  return (
    <div style={{ fontFamily: "'DM Sans', sans-serif" }}>
      <div className="flex items-center gap-3 mb-6 flex-wrap">
        <div className="flex items-center gap-2 px-3 py-2.5 rounded-xl bg-white border flex-1 min-w-48" style={{ borderColor: "rgba(13,31,60,0.1)" }}>
          <Search size={15} style={{ color: "var(--muted-foreground)" }} />
          <input type="text" placeholder="Search subjects..." value={search} onChange={(e) => setSearch(e.target.value)}
            className="bg-transparent outline-none flex-1" style={{ fontSize: 13, color: "var(--navy)" }} />
        </div>
        <button onClick={() => setShowAdd(true)}
          className="flex items-center gap-2 px-4 py-2.5 rounded-xl hover:opacity-90 transition-all"
          style={{ background: "var(--navy)", color: "#fff", fontSize: 13, fontWeight: 500 }}>
          <Plus size={15} /> Add Subject
        </button>
      </div>

      <div className="grid grid-cols-2 gap-4 mb-6">
        {[
          { label: "Total Subjects", value: subjects.length, icon: BookOpen },
          { label: "Currently Active", value: subjects.length, icon: Clock },
        ].map((stat) => (
          <div key={stat.label} className="bg-white rounded-xl p-4 border flex items-center gap-4" style={{ borderColor: "rgba(13,31,60,0.07)" }}>
            <div className="w-10 h-10 rounded-xl flex items-center justify-center" style={{ background: "rgba(13,31,60,0.07)" }}>
              <stat.icon size={18} style={{ color: "var(--navy)" }} />
            </div>
            <div>
              <div style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 700, fontSize: 22, color: "var(--navy)" }}>{stat.value}</div>
              <div style={{ fontSize: 12, color: "var(--muted-foreground)" }}>{stat.label}</div>
            </div>
          </div>
        ))}
      </div>

      <div className="grid gap-3">
        {loading ? (
          <div className="flex justify-center py-12"><div className="w-6 h-6 border-2 border-navy/30 border-t-navy rounded-full animate-spin" /></div>
        ) : filtered.map((subject, i) => (
          <motion.div key={subject.SubjectID} initial={{ opacity: 0, y: 12 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: i * 0.05 }}
            className="bg-white rounded-xl border p-4 cursor-pointer hover:shadow-sm transition-all flex items-center gap-4"
            style={{ borderColor: "rgba(13,31,60,0.08)" }}
            onClick={() => setSelected(subject)}>
            <div className="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style={{ background: "rgba(13,31,60,0.07)" }}>
              <BookOpen size={17} style={{ color: "var(--navy)" }} />
            </div>
            <div className="flex-1">
              <h4 style={{ fontSize: 14, fontWeight: 600, color: "var(--navy)" }}>{subject.SubjectName}</h4>
              <div style={{ fontSize: 12, color: "var(--muted-foreground)", fontFamily: "'DM Mono', monospace", marginTop: 2 }}>
                ID: {subject.SubjectID}
              </div>
            </div>
            <ChevronRight size={16} style={{ color: "var(--muted-foreground)" }} />
          </motion.div>
        ))}
      </div>

      <AnimatePresence>
        {selected && (
          <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} exit={{ opacity: 0 }}
            className="fixed inset-0 z-50 flex items-center justify-center p-4"
            style={{ background: "rgba(0,0,0,0.4)", backdropFilter: "blur(4px)" }}
            onClick={() => setSelected(null)}>
            <motion.div initial={{ scale: 0.95 }} animate={{ scale: 1 }} exit={{ scale: 0.95 }}
              className="bg-white rounded-2xl w-full max-w-md overflow-hidden" style={{ boxShadow: "0 24px 64px rgba(0,0,0,0.15)" }}
              onClick={(e) => e.stopPropagation()}>
              <div className="p-6" style={{ background: "var(--navy)" }}>
                <div className="flex justify-between items-start mb-3">
                  <span className="px-3 py-1 rounded-full text-white/70 text-xs font-mono border border-white/20">SUBJ-{selected.SubjectID}</span>
                  <button onClick={() => setSelected(null)} className="text-white/60 hover:text-white"><X size={18} /></button>
                </div>
                <h2 style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 700, fontSize: 22, color: "#fff" }}>{selected.SubjectName}</h2>
              </div>
              <div className="p-6 space-y-4">
                <div className="p-4 rounded-xl" style={{ background: "var(--muted)" }}>
                  <div style={{ fontSize: 11, color: "var(--muted-foreground)", marginBottom: 2 }}>Subject ID</div>
                  <div style={{ fontSize: 16, fontWeight: 600, color: "var(--navy)" }}>{selected.SubjectID}</div>
                </div>
                <div className="flex gap-3 pt-2">
                  <button onClick={() => handleDelete(selected.SubjectID)}
                    className="flex-1 py-2.5 rounded-xl border transition-all hover:bg-red-50"
                    style={{ fontSize: 13, fontWeight: 500, color: "#d4183d", borderColor: "#fecdd3" }}>
                    <Trash2 size={14} className="inline mr-1.5" /> Delete
                  </button>
                </div>
              </div>
            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>

      <AnimatePresence>
        {showAdd && (
          <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} exit={{ opacity: 0 }}
            className="fixed inset-0 z-50 flex items-center justify-center p-4"
            style={{ background: "rgba(0,0,0,0.4)", backdropFilter: "blur(4px)" }}
            onClick={() => setShowAdd(false)}>
            <motion.div initial={{ scale: 0.95 }} animate={{ scale: 1 }} exit={{ scale: 0.95 }}
              className="bg-white rounded-2xl w-full max-w-md overflow-hidden" style={{ boxShadow: "0 24px 64px rgba(0,0,0,0.15)" }}
              onClick={(e) => e.stopPropagation()}>
              <div className="flex items-center justify-between p-6 border-b" style={{ borderColor: "rgba(13,31,60,0.08)" }}>
                <h3 style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 700, fontSize: 18, color: "var(--navy)" }}>Add Subject</h3>
                <button onClick={() => setShowAdd(false)}><X size={18} style={{ color: "var(--muted-foreground)" }} /></button>
              </div>
              <form onSubmit={handleAdd} className="p-6 space-y-4">
                <div>
                  <label style={{ fontSize: 13, fontWeight: 500, color: "var(--navy)", display: "block", marginBottom: 6 }}>Subject Name</label>
                  <input type="text" placeholder="e.g. CS101 - Introduction to Programming" value={newName}
                    onChange={(e) => setNewName(e.target.value)}
                    className="w-full px-4 py-2.5 rounded-xl outline-none"
                    style={{ background: "var(--input-background)", fontSize: 13, color: "var(--navy)", border: "1.5px solid transparent" }}
                    onFocus={(e) => (e.target.style.border = "1.5px solid var(--gold)")}
                    onBlur={(e) => (e.target.style.border = "1.5px solid transparent")} required />
                </div>
                <button type="submit" disabled={saving}
                  className="w-full py-3 rounded-xl transition-all hover:opacity-90 flex items-center justify-center gap-2"
                  style={{ background: "var(--navy)", color: "#fff", fontSize: 14, fontWeight: 600 }}>
                  {saving ? <div className="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin" /> : "Create Subject"}
                </button>
              </form>
            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
}
