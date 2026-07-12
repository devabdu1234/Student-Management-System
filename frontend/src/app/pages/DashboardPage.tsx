import { useState, useEffect } from "react";
import { useNavigate } from "react-router";
import { Users, BookOpen, ClipboardCheck, FileText, TrendingUp, TrendingDown, Calendar, Clock, AlertCircle, ChevronRight, Award } from "lucide-react";
import { motion } from "motion/react";
import { AreaChart, Area, BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, PieChart, Pie, Cell } from "recharts";
import { api } from "../../lib/api";

const statCards = [
  { label: "Total Students", key: "total_students", icon: Users, color: "var(--navy)" },
  { label: "Total Subjects", key: "total_subjects", icon: BookOpen, color: "#1a3a6e" },
  { label: "Avg. Attendance", key: "attendance_rate", suffix: "%", icon: ClipboardCheck, color: "#c8a951" },
  { label: "Avg. Marks", key: "avg_marks", suffix: "%", icon: FileText, color: "#2d5016" },
];

const gradeDistribution = [
  { name: "Excellent (90+)", value: 28, color: "#0d1f3c" },
  { name: "Good (80-89)", value: 42, color: "#c8a951" },
  { name: "Average (70-79)", value: 19, color: "#3d5a8a" },
  { name: "Below (< 70)", value: 11, color: "#e2e8f2" },
];

const attendanceData = [
  { week: "Wk 1", CS101: 95, CS201: 88, MATH1: 92 },
  { week: "Wk 2", CS101: 88, CS201: 91, MATH1: 87 },
  { week: "Wk 3", CS101: 92, CS201: 85, MATH1: 90 },
  { week: "Wk 4", CS101: 84, CS201: 88, MATH1: 93 },
  { week: "Wk 5", CS101: 90, CS201: 92, MATH1: 86 },
  { week: "Wk 6", CS101: 87, CS201: 89, MATH1: 91 },
  { week: "Wk 7", CS101: 93, CS201: 86, MATH1: 88 },
  { week: "Wk 8", CS101: 91, CS201: 94, MATH1: 95 },
];

const today = new Date();
const todayStr = today.toLocaleDateString("en-US", { month: "short", day: "numeric", year: "numeric" });

export function DashboardPage() {
  const navigate = useNavigate();
  const [stats, setStats] = useState<any>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    api.dashboard().then(setStats).catch(() => {}).finally(() => setLoading(false));
  }, []);

  if (loading) {
    return <div className="flex items-center justify-center py-20"><div className="w-8 h-8 border-2 border-navy/30 border-t-navy rounded-full animate-spin" /></div>;
  }

  const upcomingClasses = [
    { subject: "CS101 - Intro to Programming", section: "BSIT 1-A", time: "7:30 AM", room: "Lab 201", students: 42 },
    { subject: "CS201 - Data Structures", section: "BSIT 2-B", time: "10:00 AM", room: "Rm 305", students: 38 },
    { subject: "MATH101 - Discrete Math", section: "BSCS 1-A", time: "1:00 PM", room: "Rm 201", students: 45 },
  ];

  return (
    <div className="space-y-6" style={{ fontFamily: "'DM Sans', sans-serif" }}>
      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {statCards.map((card, i) => {
          const val = stats?.[card.key] ?? 0;
          return (
            <motion.div key={card.label} initial={{ opacity: 0, y: 16 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: i * 0.06 }}
              className="bg-white rounded-xl p-5 border" style={{ borderColor: "rgba(13,31,60,0.06)" }}>
              <div className="flex items-start justify-between mb-4">
                <div className="w-10 h-10 rounded-xl flex items-center justify-center" style={{ background: card.color + "15" }}>
                  <card.icon size={18} style={{ color: card.color }} />
                </div>
              </div>
              <div style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 700, fontSize: 28, color: "var(--navy)" }}>{val}{card.suffix || ""}</div>
              <div style={{ fontSize: 13, color: "var(--muted-foreground)", marginTop: 2 }}>{card.label}</div>
            </motion.div>
          );
        })}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <motion.div initial={{ opacity: 0, y: 16 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: 0.25 }}
          className="lg:col-span-2 bg-white rounded-xl p-5 border" style={{ borderColor: "rgba(13,31,60,0.06)" }}>
          <div className="flex items-center justify-between mb-5">
            <div>
              <h3 style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 600, fontSize: 15, color: "var(--navy)" }}>Weekly Attendance Trend</h3>
              <p style={{ fontSize: 12, color: "var(--muted-foreground)", marginTop: 2 }}>Across all handled subjects</p>
            </div>
            <div className="flex items-center gap-4">
              {[{ label: "CS101", color: "#0d1f3c" }, { label: "CS201", color: "#c8a951" }, { label: "MATH1", color: "#3d5a8a" }].map((l) => (
                <div key={l.label} className="flex items-center gap-1.5"><div className="w-2.5 h-2.5 rounded-full" style={{ background: l.color }} /><span style={{ fontSize: 11, color: "var(--muted-foreground)" }}>{l.label}</span></div>
              ))}
            </div>
          </div>
          <ResponsiveContainer width="100%" height={200}>
            <AreaChart data={attendanceData}>
              <defs>
                <linearGradient id="g1" x1="0" y1="0" x2="0" y2="1"><stop offset="5%" stopColor="#0d1f3c" stopOpacity={0.12} /><stop offset="95%" stopColor="#0d1f3c" stopOpacity={0} /></linearGradient>
                <linearGradient id="g2" x1="0" y1="0" x2="0" y2="1"><stop offset="5%" stopColor="#c8a951" stopOpacity={0.12} /><stop offset="95%" stopColor="#c8a951" stopOpacity={0} /></linearGradient>
              </defs>
              <CartesianGrid strokeDasharray="3 3" stroke="rgba(13,31,60,0.05)" />
              <XAxis dataKey="week" tick={{ fontSize: 11, fill: "#5a6a85" }} axisLine={false} tickLine={false} />
              <YAxis domain={[75, 100]} tick={{ fontSize: 11, fill: "#5a6a85" }} axisLine={false} tickLine={false} />
              <Tooltip contentStyle={{ borderRadius: 8, border: "1px solid rgba(13,31,60,0.1)", boxShadow: "0 4px 16px rgba(0,0,0,0.1)", fontSize: 12 }} />
              <Area type="monotone" dataKey="CS101" stroke="#0d1f3c" strokeWidth={2} fill="url(#g1)" dot={false} />
              <Area type="monotone" dataKey="CS201" stroke="#c8a951" strokeWidth={2} fill="url(#g2)" dot={false} />
              <Area type="monotone" dataKey="MATH1" stroke="#3d5a8a" strokeWidth={2} fill="none" dot={false} />
            </AreaChart>
          </ResponsiveContainer>
        </motion.div>

        <motion.div initial={{ opacity: 0, y: 16 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: 0.3 }}
          className="bg-white rounded-xl p-5 border" style={{ borderColor: "rgba(13,31,60,0.06)" }}>
          <h3 style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 600, fontSize: 15, color: "var(--navy)", marginBottom: 4 }}>Grade Distribution</h3>
          <p style={{ fontSize: 12, color: "var(--muted-foreground)", marginBottom: 16 }}>Current semester overall</p>
          <ResponsiveContainer width="100%" height={150}>
            <PieChart>
              <Pie data={gradeDistribution} cx="50%" cy="50%" innerRadius={45} outerRadius={70} paddingAngle={3} dataKey="value">
                {gradeDistribution.map((entry, index) => (<Cell key={index} fill={entry.color} />))}
              </Pie>
              <Tooltip contentStyle={{ borderRadius: 8, fontSize: 12, border: "1px solid rgba(13,31,60,0.1)" }} />
            </PieChart>
          </ResponsiveContainer>
          <div className="space-y-2 mt-2">
            {gradeDistribution.map((item) => (
              <div key={item.name} className="flex items-center justify-between">
                <div className="flex items-center gap-2"><div className="w-2 h-2 rounded-full" style={{ background: item.color }} /><span style={{ fontSize: 11, color: "var(--muted-foreground)" }}>{item.name}</span></div>
                <span style={{ fontSize: 12, fontWeight: 600, color: "var(--navy)" }}>{item.value}%</span>
              </div>
            ))}
          </div>
        </motion.div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <motion.div initial={{ opacity: 0, y: 16 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: 0.35 }}
          className="bg-white rounded-xl p-5 border" style={{ borderColor: "rgba(13,31,60,0.06)" }}>
          <div className="flex items-center justify-between mb-4">
            <h3 style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 600, fontSize: 15, color: "var(--navy)" }}>Today's Schedule</h3>
            <span style={{ fontSize: 12, color: "var(--muted-foreground)" }}>{todayStr}</span>
          </div>
          <div className="space-y-3">
            {upcomingClasses.map((cls, i) => (
              <div key={i} className="flex items-start gap-3 p-3 rounded-xl cursor-pointer transition-all hover:bg-blue-50/50" style={{ background: i === 0 ? "rgba(13,31,60,0.04)" : "transparent" }}>
                <div className="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 mt-0.5" style={{ background: i === 0 ? "var(--navy)" : "var(--muted)" }}>
                  <Clock size={14} style={{ color: i === 0 ? "#fff" : "var(--muted-foreground)" }} />
                </div>
                <div className="flex-1 min-w-0">
                  <div style={{ fontSize: 13, fontWeight: 500, color: "var(--navy)" }} className="truncate">{cls.subject}</div>
                  <div style={{ fontSize: 11, color: "var(--muted-foreground)", marginTop: 2 }}>{cls.section} - {cls.room} - {cls.students} students</div>
                </div>
                <div style={{ fontSize: 12, fontWeight: 600, color: i === 0 ? "var(--gold)" : "var(--muted-foreground)", whiteSpace: "nowrap" }}>{cls.time}</div>
              </div>
            ))}
          </div>
          <button onClick={() => navigate("/attendance")}
            className="mt-4 w-full py-2.5 rounded-xl text-center transition-all hover:opacity-90"
            style={{ background: "var(--navy)", color: "#fff", fontSize: 13, fontWeight: 500 }}>Take Attendance</button>
        </motion.div>

        <motion.div initial={{ opacity: 0, y: 16 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: 0.4 }}
          className="lg:col-span-2 bg-white rounded-xl p-5 border" style={{ borderColor: "rgba(13,31,60,0.06)" }}>
          <div className="flex items-center justify-between mb-4">
            <h3 style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 600, fontSize: 15, color: "var(--navy)" }}>Recent Attendance</h3>
          </div>
          <div className="space-y-1">
            {(stats?.recent_attendance || []).slice(0, 5).map((item: any, i: number) => (
              <div key={i} className="flex items-center gap-3 p-3 rounded-xl transition-all hover:bg-gray-50 cursor-pointer">
                <div className="w-8 h-8 rounded-xl flex items-center justify-center shrink-0" style={{ background: item.Status === "present" ? "#f0fdf4" : item.Status === "absent" ? "#fff0f3" : "var(--muted)" }}>
                  <ClipboardCheck size={15} style={{ color: item.Status === "present" ? "#16a34a" : item.Status === "absent" ? "#d4183d" : "var(--muted-foreground)" }} />
                </div>
                <div className="flex-1 min-w-0">
                  <p style={{ fontSize: 13, color: "var(--navy)", fontWeight: 400 }} className="truncate">{item.StudentName} - {item.Status} ({item.SubjectName})</p>
                </div>
                <span style={{ fontSize: 11, color: "var(--muted-foreground)", whiteSpace: "nowrap" }}>{item.AttendanceDate}</span>
              </div>
            ))}
            {(!stats?.recent_attendance || stats.recent_attendance.length === 0) && (
              <p style={{ fontSize: 13, color: "var(--muted-foreground)", textAlign: "center", padding: 16 }}>No recent attendance records</p>
            )}
          </div>
        </motion.div>
      </div>
    </div>
  );
}
