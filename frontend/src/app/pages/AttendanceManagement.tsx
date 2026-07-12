import { useState, useEffect } from "react";
import { Calendar, ChevronLeft, ChevronRight, Check, X, Clock, Save, RotateCcw, Users, Search } from "lucide-react";
import { motion } from "motion/react";
import { api } from "../../lib/api";

type AttStatus = "present" | "absent" | "late" | "excused";

type Student = { StudentID: number; StudentName: string; email?: string; };
type Subject = { SubjectID: number; SubjectName: string; };

const statusConfig: Record<AttStatus, { label: string; color: string; bg: string }> = {
  present: { label: "Present", color: "#16a34a", bg: "#f0fdf4" },
  absent: { label: "Absent", color: "#d4183d", bg: "#fff0f3" },
  late: { label: "Late", color: "#ea580c", bg: "#fff7ed" },
  excused: { label: "Excused", color: "#2563eb", bg: "#eff6ff" },
};

const MONTHS = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

export function AttendanceManagement() {
  const today = new Date();
  const [subjects, setSubjects] = useState<Subject[]>([]);
  const [students, setStudents] = useState<Student[]>([]);
  const [selectedSubject, setSelectedSubject] = useState<Subject | null>(null);
  const [selectedDate, setSelectedDate] = useState(today.getDate());
  const [calMonth, setCalMonth] = useState(today.getMonth());
  const [calYear] = useState(today.getFullYear());
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [saved, setSaved] = useState(false);
  const [records, setRecords] = useState<{ id: number; name: string; status: AttStatus }[]>([]);

  useEffect(() => {
    Promise.all([api.subjects.list(), api.students.list()]).then(([subs, studs]) => {
      setSubjects(subs);
      setStudents(studs);
      if (subs.length > 0) setSelectedSubject(subs[0]);
      setRecords(studs.map((s: Student) => ({ id: s.StudentID, name: s.StudentName, status: "present" as AttStatus })));
    }).catch(() => {}).finally(() => setLoading(false));
  }, []);

  const cycleStatus = (id: number) => {
    const order: AttStatus[] = ["present", "absent", "late", "excused"];
    setRecords((prev) =>
      prev.map((r) => r.id === id ? { ...r, status: order[(order.indexOf(r.status) + 1) % order.length] } : r)
    );
    setSaved(false);
  };

  const markAll = (status: AttStatus) => {
    setRecords((prev) => prev.map((r) => ({ ...r, status })));
    setSaved(false);
  };

  const handleSave = async () => {
    if (!selectedSubject) return;
    setSaving(true);
    try {
      for (const r of records) {
        await api.attendance.create({
          StudentID: r.id,
          SubjectID: selectedSubject.SubjectID,
          AttendanceDate: `${calYear}-${String(calMonth + 1).padStart(2, '0')}-${String(selectedDate).padStart(2, '0')}`,
          Status: r.status,
        });
      }
      setSaved(true);
      setTimeout(() => setSaved(false), 3000);
    } catch (err: any) {
      alert(err.message);
    } finally {
      setSaving(false);
    }
  };

  const counts = {
    present: records.filter((r) => r.status === "present").length,
    absent: records.filter((r) => r.status === "absent").length,
    late: records.filter((r) => r.status === "late").length,
    excused: records.filter((r) => r.status === "excused").length,
  };

  if (loading) return <div className="flex justify-center py-20"><div className="w-6 h-6 border-2 border-navy/30 border-t-navy rounded-full animate-spin" /></div>;

  return (
    <div className="grid grid-cols-1 lg:grid-cols-3 gap-5" style={{ fontFamily: "'DM Sans', sans-serif" }}>
      <div className="space-y-4">
        <div className="bg-white rounded-2xl p-5 border" style={{ borderColor: "rgba(13,31,60,0.08)" }}>
          <h3 style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 600, fontSize: 14, color: "var(--navy)", marginBottom: 12 }}>Select Subject</h3>
          <div className="space-y-2" style={{ maxHeight: 240, overflowY: "auto" }}>
            {subjects.map((subj) => (
              <button key={subj.SubjectID} onClick={() => setSelectedSubject(subj)}
                className="w-full text-left p-3 rounded-xl transition-all"
                style={{ background: selectedSubject?.SubjectID === subj.SubjectID ? "var(--navy)" : "var(--muted)", color: selectedSubject?.SubjectID === subj.SubjectID ? "#fff" : "var(--navy)" }}>
                <div style={{ fontSize: 13, fontWeight: 500 }}>{subj.SubjectName}</div>
              </button>
            ))}
          </div>
        </div>
        <div className="bg-white rounded-2xl p-5 border" style={{ borderColor: "rgba(13,31,60,0.08)" }}>
          <div className="flex items-center justify-between mb-4">
            <button onClick={() => setCalMonth((m) => Math.max(0, m - 1))} className="p-1 rounded-lg hover:bg-gray-100"><ChevronLeft size={16} style={{ color: "var(--navy)" }} /></button>
            <span style={{ fontSize: 14, fontWeight: 600, color: "var(--navy)" }}>{MONTHS[calMonth]} {calYear}</span>
            <button onClick={() => setCalMonth((m) => Math.min(11, m + 1))} className="p-1 rounded-lg hover:bg-gray-100"><ChevronRight size={16} style={{ color: "var(--navy)" }} /></button>
          </div>
        </div>
      </div>

      <div className="lg:col-span-2 bg-white rounded-2xl border overflow-hidden" style={{ borderColor: "rgba(13,31,60,0.08)" }}>
        <div className="p-5 border-b" style={{ borderColor: "rgba(13,31,60,0.07)" }}>
          <div className="flex items-start justify-between flex-wrap gap-3">
            <div>
              <h3 style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 600, fontSize: 16, color: "var(--navy)" }}>
                {selectedSubject?.SubjectName || "Select a subject"}
              </h3>
              <p style={{ fontSize: 12, color: "var(--muted-foreground)", marginTop: 3 }}>
                {MONTHS[calMonth]} {selectedDate}, {calYear} · {records.length} students
              </p>
            </div>
            <div className="flex items-center gap-2">
              <button onClick={() => markAll("present")} className="px-3 py-1.5 rounded-lg text-xs font-medium transition-all hover:opacity-80"
                style={{ background: "#f0fdf4", color: "#16a34a" }}>Mark All Present</button>
              <button onClick={() => { setRecords(students.map((s: Student) => ({ id: s.StudentID, name: s.StudentName, status: "present" as AttStatus }))); setSaved(false); }}
                className="p-2 rounded-lg hover:bg-gray-100 transition-all">
                <RotateCcw size={14} style={{ color: "var(--muted-foreground)" }} />
              </button>
            </div>
          </div>
          <div className="flex items-center gap-4 mt-4 flex-wrap">
            {(["present", "absent", "late", "excused"] as AttStatus[]).map((s) => {
              const cfg = statusConfig[s];
              return (
                <div key={s} className="flex items-center gap-1.5">
                  <div className="w-5 h-5 rounded-md flex items-center justify-center" style={{ background: cfg.bg, color: cfg.color }}>
                    {s === "present" || s === "excused" ? <Check size={12} /> : s === "absent" ? <X size={12} /> : <Clock size={12} />}
                  </div>
                  <span style={{ fontSize: 12, color: "var(--muted-foreground)" }}>{cfg.label}: <strong style={{ color: "var(--navy)" }}>{counts[s]}</strong></span>
                </div>
              );
            })}
            <div className="ml-auto flex items-center gap-1.5">
              <Users size={13} style={{ color: "var(--muted-foreground)" }} />
              <span style={{ fontSize: 12, color: "var(--muted-foreground)" }}>Total: <strong style={{ color: "var(--navy)" }}>{records.length}</strong></span>
            </div>
          </div>
        </div>

        <div className="overflow-y-auto" style={{ maxHeight: 460 }}>
          {records.map((record, i) => {
            const cfg = statusConfig[record.status];
            return (
              <motion.div key={record.id} initial={{ opacity: 0 }} animate={{ opacity: 1 }} transition={{ delay: i * 0.02 }}
                className="flex items-center gap-4 px-5 py-3 border-b last:border-0 hover:bg-gray-50/60 transition-colors"
                style={{ borderColor: "rgba(13,31,60,0.05)" }}>
                <div className="w-7 h-7 rounded-lg flex items-center justify-center shrink-0"
                  style={{ background: `hsl(${record.id * 37}, 60%, 90%)`, color: `hsl(${record.id * 37}, 60%, 35%)`, fontSize: 11, fontWeight: 700 }}>
                  {record.name.split(" ").map((n) => n[0]).join("").slice(0, 2)}
                </div>
                <div className="flex-1 min-w-0">
                  <div style={{ fontSize: 14, fontWeight: 500, color: "var(--navy)" }}>{record.name}</div>
                </div>
                <div className="flex items-center gap-1.5">
                  {(["present", "late", "absent", "excused"] as AttStatus[]).map((s) => {
                    const c = statusConfig[s];
                    const active = record.status === s;
                    return (
                      <button key={s} onClick={() => setRecords((prev) => prev.map((r) => r.id === record.id ? { ...r, status: s } : r))}
                        className="px-2.5 py-1 rounded-lg transition-all"
                        style={{ background: active ? c.bg : "transparent", color: active ? c.color : "var(--muted-foreground)", fontSize: 11, fontWeight: active ? 600 : 400, border: active ? `1px solid ${c.color}30` : "1px solid transparent" }}>
                        {c.label}
                      </button>
                    );
                  })}
                </div>
              </motion.div>
            );
          })}
        </div>

        <div className="p-5 border-t flex items-center justify-between" style={{ borderColor: "rgba(13,31,60,0.07)" }}>
          <div style={{ fontSize: 13, color: "var(--muted-foreground)" }}>
            Rate: <strong style={{ color: counts.present / records.length >= 0.8 ? "#16a34a" : "#ea580c" }}>
              {records.length > 0 ? Math.round((counts.present / records.length) * 100) : 0}%
            </strong> present
          </div>
          <button onClick={handleSave} disabled={saving || !selectedSubject}
            className="flex items-center gap-2 px-5 py-2.5 rounded-xl transition-all hover:opacity-90"
            style={{ background: saved ? "#16a34a" : "var(--navy)", color: "#fff", fontSize: 14, fontWeight: 500 }}>
            {saving ? <div className="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin" /> : saved ? <><Check size={15} /> Saved!</> : <><Save size={15} /> Save Attendance</>}
          </button>
        </div>
      </div>
    </div>
  );
}
