import { useState } from "react";
import { ShieldCheck, ShieldAlert, ShieldX, Search, Filter, AlertTriangle, CheckCircle2, XCircle, Info } from "lucide-react";
import { motion } from "motion/react";

type EligStatus = "eligible" | "warning" | "ineligible";

type StudentElig = {
  id: string; name: string; studentNo: string; course: string; year: number; section: string;
  attendance: number; quiz: number; midterm: number; lab: number;
  status: EligStatus; remarks: string;
};

const students: StudentElig[] = [
  { id: "1", name: "Maria Santos", studentNo: "2021-00421", course: "BSIT", year: 2, section: "2-A", attendance: 94, quiz: 92, midterm: 88, lab: 95, status: "eligible", remarks: "All requirements met" },
  { id: "2", name: "Juan Dela Cruz", studentNo: "2021-00422", course: "BSCS", year: 2, section: "2-B", attendance: 78, quiz: 75, midterm: 72, lab: 80, status: "warning", remarks: "Attendance slightly below threshold" },
  { id: "3", name: "Ana Reyes", studentNo: "2022-00101", course: "BSIT", year: 1, section: "1-A", attendance: 65, quiz: 58, midterm: 52, lab: 60, status: "ineligible", remarks: "Attendance < 75%; Midterm failing" },
  { id: "4", name: "Carlos Mendoza", studentNo: "2020-00350", course: "BSCS", year: 3, section: "3-A", attendance: 88, quiz: 90, midterm: 91, lab: 88, status: "eligible", remarks: "All requirements met" },
  { id: "5", name: "Sofia Lim", studentNo: "2021-00423", course: "BSIT", year: 2, section: "2-A", attendance: 96, quiz: 95, midterm: 94, lab: 97, status: "eligible", remarks: "Excellent standing" },
  { id: "6", name: "Miguel Torres", studentNo: "2022-00102", course: "BSIT", year: 1, section: "1-B", attendance: 72, quiz: 68, midterm: 65, lab: 70, status: "warning", remarks: "Multiple metrics borderline" },
  { id: "7", name: "Isabella Garcia", studentNo: "2021-00424", course: "BSCS", year: 2, section: "2-A", attendance: 55, quiz: 50, midterm: 48, lab: 55, status: "ineligible", remarks: "Critical: attendance and grades below minimum" },
  { id: "8", name: "Rafael Bautista", studentNo: "2020-00351", course: "BSIT", year: 3, section: "3-B", attendance: 91, quiz: 85, midterm: 87, lab: 89, status: "eligible", remarks: "All requirements met" },
  { id: "9", name: "Camille Villanueva", studentNo: "2022-00103", course: "BSCS", year: 1, section: "1-A", attendance: 83, quiz: 80, midterm: 76, lab: 82, status: "eligible", remarks: "All requirements met" },
  { id: "10", name: "Patrick Navarro", studentNo: "2019-00200", course: "BSIT", year: 4, section: "4-A", attendance: 70, quiz: 72, midterm: 68, lab: 73, status: "warning", remarks: "Attendance borderline; midterm grade low" },
];

const THRESHOLDS = { attendance: 75, quiz: 60, midterm: 60, lab: 60 };

const statusConfig: Record<EligStatus, { label: string; color: string; bg: string; borderColor: string; icon: React.ReactNode }> = {
  eligible: { label: "Eligible", color: "#16a34a", bg: "#f0fdf4", borderColor: "#bbf7d0", icon: <ShieldCheck size={14} /> },
  warning: { label: "Warning", color: "#ea580c", bg: "#fff7ed", borderColor: "#fed7aa", icon: <ShieldAlert size={14} /> },
  ineligible: { label: "Ineligible", color: "#d4183d", bg: "#fff0f3", borderColor: "#fecdd3", icon: <ShieldX size={14} /> },
};

function Gauge({ value, threshold, label }: { value: number; threshold: number; label: string }) {
  const ok = value >= threshold;
  const pct = Math.min(value, 100);
  return (
    <div className="space-y-1">
      <div className="flex justify-between items-center">
        <span style={{ fontSize: 11, color: "var(--muted-foreground)" }}>{label}</span>
        <span style={{ fontSize: 12, fontWeight: 600, color: ok ? "#16a34a" : "#d4183d", fontFamily: "'DM Mono', monospace" }}>{value}%</span>
      </div>
      <div className="h-1.5 rounded-full overflow-hidden" style={{ background: "var(--muted)" }}>
        <div className="h-full rounded-full transition-all" style={{ width: `${pct}%`, background: ok ? "#16a34a" : "#d4183d" }} />
      </div>
    </div>
  );
}

export function EligibilityTracking() {
  const [search, setSearch] = useState("");
  const [filter, setFilter] = useState<string>("all");
  const [expanded, setExpanded] = useState<string | null>(null);

  const counts = {
    eligible: students.filter((s) => s.status === "eligible").length,
    warning: students.filter((s) => s.status === "warning").length,
    ineligible: students.filter((s) => s.status === "ineligible").length,
  };

  const filtered = students.filter(
    (s) =>
      (s.name.toLowerCase().includes(search.toLowerCase()) || s.studentNo.includes(search)) &&
      (filter === "all" || s.status === filter)
  );

  return (
    <div style={{ fontFamily: "'DM Sans', sans-serif" }}>
      {/* Summary Cards */}
      <div className="grid grid-cols-3 gap-4 mb-5">
        {(["eligible", "warning", "ineligible"] as EligStatus[]).map((status) => {
          const cfg = statusConfig[status];
          return (
            <button
              key={status}
              onClick={() => setFilter(filter === status ? "all" : status)}
              className="bg-white rounded-xl p-5 border text-left transition-all hover:shadow-sm"
              style={{ borderColor: filter === status ? cfg.color + "60" : "rgba(13,31,60,0.08)", boxShadow: filter === status ? `0 0 0 2px ${cfg.color}20` : undefined }}
            >
              <div className="flex items-center justify-between mb-3">
                <div className="w-10 h-10 rounded-xl flex items-center justify-center" style={{ background: cfg.bg, color: cfg.color }}>
                  {cfg.icon}
                </div>
                <span style={{ fontSize: 11, color: cfg.color, fontWeight: 600, background: cfg.bg, padding: "2px 8px", borderRadius: 999 }}>{cfg.label}</span>
              </div>
              <div style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 700, fontSize: 32, color: "var(--navy)" }}>{counts[status]}</div>
              <div style={{ fontSize: 12, color: "var(--muted-foreground)", marginTop: 2 }}>of {students.length} students</div>
            </button>
          );
        })}
      </div>

      {/* Thresholds Info */}
      <div className="flex items-start gap-3 p-4 rounded-xl mb-5" style={{ background: "rgba(13,31,60,0.04)", border: "1px solid rgba(13,31,60,0.08)" }}>
        <Info size={15} style={{ color: "var(--navy)", marginTop: 1 }} />
        <div style={{ fontSize: 12, color: "var(--muted-foreground)", lineHeight: 1.7 }}>
          <strong style={{ color: "var(--navy)" }}>Eligibility Thresholds:</strong> Attendance ≥ 75% · Quiz Average ≥ 60% · Midterm ≥ 60% · Lab Activities ≥ 60% · Students meeting all criteria are eligible for the final examination.
        </div>
      </div>

      {/* Toolbar */}
      <div className="flex items-center gap-3 mb-4 flex-wrap">
        <div className="flex items-center gap-2 px-3 py-2.5 rounded-xl bg-white border flex-1 min-w-48" style={{ borderColor: "rgba(13,31,60,0.1)" }}>
          <Search size={15} style={{ color: "var(--muted-foreground)" }} />
          <input type="text" placeholder="Search students..." value={search}
            onChange={(e) => setSearch(e.target.value)} className="bg-transparent outline-none flex-1"
            style={{ fontSize: 13, color: "var(--navy)" }} />
        </div>
        <span style={{ fontSize: 13, color: "var(--muted-foreground)" }}>{filtered.length} students</span>
      </div>

      {/* Student List */}
      <div className="space-y-3">
        {filtered.map((student, i) => {
          const cfg = statusConfig[student.status];
          const isOpen = expanded === student.id;
          return (
            <motion.div
              key={student.id}
              initial={{ opacity: 0, y: 10 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: i * 0.04 }}
              className="bg-white rounded-xl border overflow-hidden"
              style={{ borderColor: isOpen ? cfg.color + "40" : "rgba(13,31,60,0.08)" }}
            >
              <button
                onClick={() => setExpanded(isOpen ? null : student.id)}
                className="w-full flex items-center gap-4 p-4 text-left hover:bg-gray-50/50 transition-colors"
              >
                <div className="w-9 h-9 rounded-xl flex items-center justify-center shrink-0"
                  style={{ background: `hsl(${parseInt(student.id) * 37}, 60%, 90%)`, color: `hsl(${parseInt(student.id) * 37}, 60%, 35%)`, fontSize: 12, fontWeight: 700 }}>
                  {student.name.split(" ").map((n) => n[0]).join("").slice(0, 2)}
                </div>
                <div className="flex-1 min-w-0">
                  <div style={{ fontSize: 14, fontWeight: 500, color: "var(--navy)" }}>{student.name}</div>
                  <div style={{ fontSize: 11, color: "var(--muted-foreground)", fontFamily: "'DM Mono', monospace" }}>{student.studentNo} · {student.course} {student.year}-{student.section}</div>
                </div>

                <div className="hidden md:flex items-center gap-6">
                  {[
                    { label: "Att.", value: student.attendance, threshold: THRESHOLDS.attendance },
                    { label: "Quiz", value: student.quiz, threshold: THRESHOLDS.quiz },
                    { label: "Midterm", value: student.midterm, threshold: THRESHOLDS.midterm },
                  ].map((m) => (
                    <div key={m.label} className="text-center">
                      <div style={{ fontSize: 14, fontWeight: 700, color: m.value >= m.threshold ? "#16a34a" : "#d4183d", fontFamily: "'DM Mono', monospace" }}>
                        {m.value}%
                      </div>
                      <div style={{ fontSize: 10, color: "var(--muted-foreground)" }}>{m.label}</div>
                    </div>
                  ))}
                </div>

                <span className="px-3 py-1.5 rounded-full flex items-center gap-1.5 shrink-0"
                  style={{ background: cfg.bg, color: cfg.color, fontSize: 12, fontWeight: 600 }}>
                  {cfg.icon} {cfg.label}
                </span>
              </button>

              {isOpen && (
                <motion.div
                  initial={{ height: 0, opacity: 0 }}
                  animate={{ height: "auto", opacity: 1 }}
                  exit={{ height: 0, opacity: 0 }}
                  className="border-t px-4 pb-4 pt-3"
                  style={{ borderColor: "rgba(13,31,60,0.07)" }}
                >
                  <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                    <Gauge value={student.attendance} threshold={THRESHOLDS.attendance} label="Attendance" />
                    <Gauge value={student.quiz} threshold={THRESHOLDS.quiz} label="Quiz Average" />
                    <Gauge value={student.midterm} threshold={THRESHOLDS.midterm} label="Midterm" />
                    <Gauge value={student.lab} threshold={THRESHOLDS.lab} label="Lab Activities" />
                  </div>
                  <div className="flex items-center justify-between">
                    <div className="flex items-start gap-2">
                      <AlertTriangle size={13} style={{ color: cfg.color, marginTop: 2 }} />
                      <span style={{ fontSize: 12, color: "var(--muted-foreground)" }}>{student.remarks}</span>
                    </div>
                    {student.status !== "eligible" && (
                      <button className="px-3 py-1.5 rounded-lg text-xs font-medium"
                        style={{ background: "var(--navy)", color: "#fff" }}>
                        Send Notice
                      </button>
                    )}
                  </div>
                </motion.div>
              )}
            </motion.div>
          );
        })}
      </div>
    </div>
  );
}
