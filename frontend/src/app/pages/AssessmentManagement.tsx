import { useState, useEffect } from "react";
import { Plus, Search, Filter, FileText, Award, TrendingUp, Edit2, Trash2, X, CheckCircle2 } from "lucide-react";
import { motion, AnimatePresence } from "motion/react";
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer } from "recharts";
import { api } from "../../lib/api";

type Exam = {
  ExamID: number; ExamName: string; SubjectID: number; ExamDate: string;
  SubjectName?: string;
};

type ExamResult = {
  ResultID: number; ExamID: number; StudentID: number; ObtainedMarks: number;
  ExamName?: string; StudentName?: string;
};

export function AssessmentManagement() {
  const [exams, setExams] = useState<Exam[]>([]);
  const [results, setResults] = useState<ExamResult[]>([]);
  const [search, setSearch] = useState("");
  const [filter, setFilter] = useState("all");
  const [loading, setLoading] = useState(true);
  const [tab, setTab] = useState<"list" | "grades">("list");
  const [showAdd, setShowAdd] = useState(false);
  const [form, setForm] = useState({ ExamName: "", SubjectID: "", ExamDate: new Date().toISOString().split("T")[0] });
  const [saving, setSaving] = useState(false);

  const load = () => {
    setLoading(true);
    Promise.all([api.exams.list(), api.examresults.list()]).then(([e, r]) => {
      setExams(e);
      setResults(r);
    }).catch(() => {}).finally(() => setLoading(false));
  };

  useEffect(() => { load(); }, []);

  const filtered = exams.filter((a) =>
    (a.ExamName?.toLowerCase().includes(search.toLowerCase()) || a.SubjectName?.toLowerCase().includes(search.toLowerCase())) &&
    (filter === "all" || true)
  );

  const handleAdd = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!form.ExamName || !form.SubjectID) return;
    setSaving(true);
    try {
      await api.exams.create({ ExamName: form.ExamName, SubjectID: parseInt(form.SubjectID), ExamDate: form.ExamDate });
      setShowAdd(false);
      setForm({ ExamName: "", SubjectID: "", ExamDate: new Date().toISOString().split("T")[0] });
      load();
    } catch (err: any) { alert(err.message); }
    finally { setSaving(false); }
  };

  const getStats = (examId: number) => {
    const scores = results.filter((r) => r.ExamID === examId).map((r) => r.ObtainedMarks);
    if (!scores.length) return null;
    const avg = scores.reduce((a, b) => a + b, 0) / scores.length;
    const highest = Math.max(...scores);
    const lowest = Math.min(...scores);
    const maxScore = Math.max(...scores, 100);
    return { avg, highest, lowest, total: scores.length, maxScore };
  };

  return (
    <div style={{ fontFamily: "'DM Sans', sans-serif" }}>
      <div className="flex items-center gap-1 mb-5 p-1 rounded-xl w-fit" style={{ background: "var(--muted)" }}>
        {[{ key: "list", label: "Assessments" }, { key: "grades", label: "Grade Sheet" }].map((t) => (
          <button key={t.key} onClick={() => setTab(t.key as "list" | "grades")}
            className="px-5 py-2 rounded-lg transition-all"
            style={{ background: tab === t.key ? "#fff" : "transparent", color: tab === t.key ? "var(--navy)" : "var(--muted-foreground)", fontSize: 13, fontWeight: tab === t.key ? 600 : 400, boxShadow: tab === t.key ? "0 1px 4px rgba(13,31,60,0.1)" : "none" }}>
            {t.label}
          </button>
        ))}
      </div>

      {tab === "list" && (
        <>
          <div className="flex items-center gap-3 mb-5 flex-wrap">
            <div className="flex items-center gap-2 px-3 py-2.5 rounded-xl bg-white border flex-1 min-w-48" style={{ borderColor: "rgba(13,31,60,0.1)" }}>
              <Search size={15} style={{ color: "var(--muted-foreground)" }} />
              <input type="text" placeholder="Search assessments..." value={search} onChange={(e) => setSearch(e.target.value)}
                className="bg-transparent outline-none flex-1" style={{ fontSize: 13, color: "var(--navy)" }} />
            </div>
            <button onClick={() => setShowAdd(true)}
              className="flex items-center gap-2 px-4 py-2.5 rounded-xl hover:opacity-90 transition-all"
              style={{ background: "var(--navy)", color: "#fff", fontSize: 13, fontWeight: 500 }}>
              <Plus size={15} /> Add Assessment
            </button>
          </div>

          <div className="grid grid-cols-4 gap-4 mb-5">
            {[
              { label: "Total Assessments", value: exams.length, icon: FileText },
              { label: "With Scores", value: new Set(results.map((r) => r.ExamID)).size, icon: CheckCircle2 },
              { label: "Students Graded", value: results.length, icon: Award },
              { label: "Avg Score", value: results.length > 0 ? (results.reduce((a, r) => a + r.ObtainedMarks, 0) / results.length).toFixed(1) : "-", icon: TrendingUp },
            ].map((stat) => (
              <div key={stat.label} className="bg-white rounded-xl p-4 border flex items-center gap-3" style={{ borderColor: "rgba(13,31,60,0.07)" }}>
                <div className="w-9 h-9 rounded-xl flex items-center justify-center" style={{ background: "rgba(13,31,60,0.07)" }}>
                  <stat.icon size={16} style={{ color: "var(--navy)" }} />
                </div>
                <div>
                  <div style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 700, fontSize: 20, color: "var(--navy)" }}>{stat.value}</div>
                  <div style={{ fontSize: 11, color: "var(--muted-foreground)" }}>{stat.label}</div>
                </div>
              </div>
            ))}
          </div>

          <div className="space-y-3">
            {loading ? (
              <div className="flex justify-center py-12"><div className="w-6 h-6 border-2 border-navy/30 border-t-navy rounded-full animate-spin" /></div>
            ) : filtered.map((assess, i) => {
              const stats = getStats(assess.ExamID);
              return (
                <motion.div key={assess.ExamID} initial={{ opacity: 0, y: 12 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: i * 0.05 }}
                  className="bg-white rounded-xl border p-4" style={{ borderColor: "rgba(13,31,60,0.08)" }}>
                  <div className="flex items-start gap-4">
                    <div className="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style={{ background: "rgba(13,31,60,0.07)" }}>
                      <FileText size={17} style={{ color: "var(--navy)" }} />
                    </div>
                    <div className="flex-1 min-w-0">
                      <div className="flex items-center gap-2 flex-wrap">
                        <h4 style={{ fontSize: 14, fontWeight: 600, color: "var(--navy)" }}>{assess.ExamName}</h4>
                        <span className="px-2 py-0.5 rounded-full" style={{ background: "rgba(13,31,60,0.07)", color: "var(--navy)", fontSize: 11, fontWeight: 600 }}>Exam</span>
                      </div>
                      <div className="flex items-center gap-4 mt-1.5 flex-wrap">
                        <span style={{ fontSize: 12, color: "var(--muted-foreground)" }}>{assess.SubjectName || `Subject #${assess.SubjectID}`} · {assess.ExamDate?.slice(0, 10)}</span>
                      </div>
                    </div>
                    {stats && (
                      <div className="flex items-center gap-6 shrink-0">
                        <div className="text-right">
                          <div style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 700, fontSize: 18, color: "var(--navy)" }}>
                            {stats.avg.toFixed(1)}
                          </div>
                          <div style={{ fontSize: 11, color: "var(--muted-foreground)" }}>Avg Score</div>
                        </div>
                        <div className="text-right">
                          <div style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 700, fontSize: 18, color: "#16a34a" }}>
                            {stats.total}
                          </div>
                          <div style={{ fontSize: 11, color: "var(--muted-foreground)" }}>Graded</div>
                        </div>
                      </div>
                    )}
                    {!stats && (
                      <span style={{ fontSize: 12, color: "var(--muted-foreground)" }}>No scores yet</span>
                    )}
                  </div>
                </motion.div>
              );
            })}
          </div>
        </>
      )}

      {tab === "grades" && (
        <div className="bg-white rounded-2xl border overflow-hidden" style={{ borderColor: "rgba(13,31,60,0.08)" }}>
          <div className="p-5 border-b" style={{ borderColor: "rgba(13,31,60,0.07)" }}>
            <h3 style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 600, fontSize: 16, color: "var(--navy)" }}>Score Distribution</h3>
            <p style={{ fontSize: 13, color: "var(--muted-foreground)", marginTop: 2 }}>All exam results</p>
          </div>
          <div className="p-5">
            <ResponsiveContainer width="100%" height={240}>
              <BarChart data={
                results.length > 0
                  ? [{ range: "0-49", count: results.filter(r => r.ObtainedMarks < 50).length },
                     { range: "50-69", count: results.filter(r => r.ObtainedMarks >= 50 && r.ObtainedMarks < 70).length },
                     { range: "70-89", count: results.filter(r => r.ObtainedMarks >= 70 && r.ObtainedMarks < 90).length },
                     { range: "90-100", count: results.filter(r => r.ObtainedMarks >= 90).length }]
                  : [{ range: "0-49", count: 0 }, { range: "50-69", count: 0 }, { range: "70-89", count: 0 }, { range: "90-100", count: 0 }]
              }>
                <CartesianGrid strokeDasharray="3 3" stroke="rgba(13,31,60,0.05)" />
                <XAxis dataKey="range" tick={{ fontSize: 12, fill: "#5a6a85" }} axisLine={false} tickLine={false} />
                <YAxis tick={{ fontSize: 12, fill: "#5a6a85" }} axisLine={false} tickLine={false} />
                <Tooltip contentStyle={{ borderRadius: 8, fontSize: 12, border: "1px solid rgba(13,31,60,0.1)" }} />
                <Bar dataKey="count" fill="#0d1f3c" radius={[4, 4, 0, 0]} />
              </BarChart>
            </ResponsiveContainer>
          </div>
          <div className="overflow-x-auto">
            <table className="w-full">
              <thead>
                <tr style={{ background: "var(--muted)", borderTop: "1px solid rgba(13,31,60,0.07)" }}>
                  {["Student", "Exam", "Score", "Status"].map((h) => (
                    <th key={h} className="px-4 py-3 text-left" style={{ fontSize: 11, fontWeight: 600, color: "var(--muted-foreground)", letterSpacing: "0.05em", textTransform: "uppercase" }}>{h}</th>
                  ))}
                </tr>
              </thead>
              <tbody>
                {results.slice(0, 50).map((row, i) => {
                  const passed = row.ObtainedMarks >= 60;
                  return (
                    <tr key={row.ResultID} className="border-t hover:bg-gray-50/50" style={{ borderColor: "rgba(13,31,60,0.05)" }}>
                      <td className="px-4 py-3" style={{ fontSize: 14, fontWeight: 500, color: "var(--navy)" }}>{row.StudentName || `Student #${row.StudentID}`}</td>
                      <td className="px-4 py-3" style={{ fontSize: 13, color: "var(--navy)" }}>{row.ExamName || `Exam #${row.ExamID}`}</td>
                      <td className="px-4 py-3" style={{ fontSize: 13, fontWeight: 600, color: "var(--navy)", fontFamily: "'DM Mono', monospace" }}>{row.ObtainedMarks}</td>
                      <td className="px-4 py-3">
                        <span className="px-2.5 py-1 rounded-full" style={{ background: passed ? "#f0fdf4" : "#fff0f3", color: passed ? "#16a34a" : "#d4183d", fontSize: 11, fontWeight: 600 }}>
                          {passed ? "Passing" : "Failing"}
                        </span>
                      </td>
                    </tr>
                  );
                })}
              </tbody>
            </table>
          </div>
        </div>
      )}

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
                <h3 style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 700, fontSize: 18, color: "var(--navy)" }}>Add Assessment</h3>
                <button onClick={() => setShowAdd(false)}><X size={18} style={{ color: "var(--muted-foreground)" }} /></button>
              </div>
              <form onSubmit={handleAdd} className="p-6 space-y-4">
                <div>
                  <label style={{ fontSize: 13, fontWeight: 500, color: "var(--navy)", display: "block", marginBottom: 6 }}>Exam Name</label>
                  <input type="text" placeholder="e.g. Midterm Examination" value={form.ExamName}
                    onChange={(e) => setForm((p) => ({ ...p, ExamName: e.target.value }))}
                    className="w-full px-4 py-2.5 rounded-xl outline-none"
                    style={{ background: "var(--input-background)", fontSize: 13, color: "var(--navy)", border: "1.5px solid transparent" }}
                    onFocus={(e) => (e.target.style.border = "1.5px solid var(--gold)")}
                    onBlur={(e) => (e.target.style.border = "1.5px solid transparent")} required />
                </div>
                <div>
                  <label style={{ fontSize: 13, fontWeight: 500, color: "var(--navy)", display: "block", marginBottom: 6 }}>Subject ID</label>
                  <input type="number" placeholder="Subject ID" value={form.SubjectID}
                    onChange={(e) => setForm((p) => ({ ...p, SubjectID: e.target.value }))}
                    className="w-full px-4 py-2.5 rounded-xl outline-none"
                    style={{ background: "var(--input-background)", fontSize: 13, color: "var(--navy)", border: "1.5px solid transparent" }}
                    onFocus={(e) => (e.target.style.border = "1.5px solid var(--gold)")}
                    onBlur={(e) => (e.target.style.border = "1.5px solid transparent")} required />
                </div>
                <div>
                  <label style={{ fontSize: 13, fontWeight: 500, color: "var(--navy)", display: "block", marginBottom: 6 }}>Exam Date</label>
                  <input type="date" value={form.ExamDate}
                    onChange={(e) => setForm((p) => ({ ...p, ExamDate: e.target.value }))}
                    className="w-full px-4 py-2.5 rounded-xl outline-none"
                    style={{ background: "var(--input-background)", fontSize: 13, color: "var(--navy)", border: "1.5px solid transparent" }}
                    onFocus={(e) => (e.target.style.border = "1.5px solid var(--gold)")}
                    onBlur={(e) => (e.target.style.border = "1.5px solid transparent")} required />
                </div>
                <button type="submit" disabled={saving}
                  className="w-full py-3 rounded-xl transition-all hover:opacity-90 flex items-center justify-center gap-2"
                  style={{ background: "var(--navy)", color: "#fff", fontSize: 14, fontWeight: 600 }}>
                  {saving ? <div className="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin" /> : "Create Assessment"}
                </button>
              </form>
            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
}
