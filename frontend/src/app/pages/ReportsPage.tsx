import { useState } from "react";
import { BarChart3, Download, TrendingUp, Users, BookOpen, Calendar, Filter } from "lucide-react";
import { motion } from "motion/react";
import {
  BarChart, Bar, LineChart, Line, XAxis, YAxis, CartesianGrid,
  Tooltip, ResponsiveContainer, Legend, RadarChart, Radar,
  PolarGrid, PolarAngleAxis, PolarRadiusAxis
} from "recharts";

const attendanceBySubject = [
  { subject: "CS101", week1: 95, week2: 88, week3: 92, week4: 84, week5: 90, week6: 87, week7: 93, week8: 91 },
  { subject: "CS201", week1: 88, week2: 91, week3: 85, week4: 88, week5: 92, week6: 89, week7: 86, week8: 94 },
  { subject: "MATH101", week1: 92, week2: 87, week3: 90, week4: 93, week5: 86, week6: 91, week7: 88, week8: 95 },
];

const weeklyTrend = [
  { name: "Wk 1", avg: 92 }, { name: "Wk 2", avg: 89 }, { name: "Wk 3", avg: 89 },
  { name: "Wk 4", avg: 88 }, { name: "Wk 5", avg: 89 }, { name: "Wk 6", avg: 89 },
  { name: "Wk 7", avg: 89 }, { name: "Wk 8", avg: 93 },
];

const gradeData = [
  { subject: "CS101", avg: 78, highest: 98, lowest: 52, passing: 85 },
  { subject: "CS201", avg: 74, highest: 95, lowest: 48, passing: 79 },
  { subject: "MATH101", avg: 80, highest: 97, lowest: 60, passing: 88 },
  { subject: "CS301", avg: 76, highest: 94, lowest: 55, passing: 82 },
  { subject: "CS401", avg: 72, highest: 92, lowest: 45, passing: 75 },
];

const radarData = [
  { metric: "Attendance", CS101: 90, CS201: 88, MATH101: 91 },
  { metric: "Quizzes", CS101: 78, CS201: 74, MATH101: 82 },
  { metric: "Midterm", CS101: 80, CS201: 72, MATH101: 81 },
  { metric: "Labs", CS101: 85, CS201: 88, MATH101: 77 },
  { metric: "Eligibility", CS101: 88, CS201: 82, MATH101: 90 },
];

const reportTypes = [
  { id: "attendance", label: "Attendance Report", icon: Calendar, desc: "Weekly and monthly attendance summary per subject" },
  { id: "grades", label: "Grade Summary", icon: BarChart3, desc: "Consolidated grades and performance overview" },
  { id: "eligibility", label: "Eligibility Report", icon: Users, desc: "Final exam eligibility status list" },
  { id: "subject", label: "Subject Report", icon: BookOpen, desc: "Detailed subject performance breakdown" },
];

export function ReportsPage() {
  const [activeReport, setActiveReport] = useState("attendance");
  const [semester, setSemester] = useState("2nd Sem 2024-25");

  return (
    <div style={{ fontFamily: "'DM Sans', sans-serif" }}>
      {/* Toolbar */}
      <div className="flex items-center gap-3 mb-5 flex-wrap">
        <select
          value={semester}
          onChange={(e) => setSemester(e.target.value)}
          className="px-3 py-2.5 rounded-xl bg-white border outline-none cursor-pointer"
          style={{ fontSize: 13, color: "var(--navy)", borderColor: "rgba(13,31,60,0.1)" }}
        >
          <option>2nd Sem 2024-25</option>
          <option>1st Sem 2024-25</option>
          <option>2nd Sem 2023-24</option>
        </select>
        <button className="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border transition-all hover:bg-gray-50 ml-auto"
          style={{ fontSize: 13, color: "var(--navy)", borderColor: "rgba(13,31,60,0.1)" }}>
          <Download size={15} /> Export All Reports
        </button>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-5">
        {reportTypes.map((rt) => (
          <button
            key={rt.id}
            onClick={() => setActiveReport(rt.id)}
            className="bg-white rounded-xl p-4 border text-left transition-all hover:shadow-sm"
            style={{
              borderColor: activeReport === rt.id ? "var(--navy)" : "rgba(13,31,60,0.08)",
              boxShadow: activeReport === rt.id ? "0 0 0 2px rgba(13,31,60,0.1)" : undefined,
            }}
          >
            <div className="w-9 h-9 rounded-xl flex items-center justify-center mb-3"
              style={{ background: activeReport === rt.id ? "var(--navy)" : "var(--muted)" }}>
              <rt.icon size={16} style={{ color: activeReport === rt.id ? "#fff" : "var(--navy)" }} />
            </div>
            <div style={{ fontSize: 13, fontWeight: 600, color: "var(--navy)", marginBottom: 4 }}>{rt.label}</div>
            <div style={{ fontSize: 11, color: "var(--muted-foreground)", lineHeight: 1.5 }}>{rt.desc}</div>
          </button>
        ))}
      </div>

      {/* Summary Stats */}
      <div className="grid grid-cols-4 gap-4 mb-5">
        {[
          { label: "Avg. Attendance", value: "87.4%", change: "+2.1%" },
          { label: "Class Pass Rate", value: "82.3%", change: "+1.8%" },
          { label: "Avg. GPA", value: "1.95", change: "-0.05" },
          { label: "At-Risk Students", value: "7", change: "-3" },
        ].map((stat) => (
          <div key={stat.label} className="bg-white rounded-xl p-4 border" style={{ borderColor: "rgba(13,31,60,0.07)" }}>
            <div style={{ fontSize: 11, color: "var(--muted-foreground)", marginBottom: 6, textTransform: "uppercase", letterSpacing: "0.05em", fontWeight: 600 }}>{stat.label}</div>
            <div style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 700, fontSize: 26, color: "var(--navy)" }}>{stat.value}</div>
            <div style={{ fontSize: 12, color: "#16a34a", marginTop: 4 }}>{stat.change} vs last sem</div>
          </div>
        ))}
      </div>

      {/* Charts */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
        {/* Weekly Attendance Trend */}
        <div className="bg-white rounded-2xl p-5 border" style={{ borderColor: "rgba(13,31,60,0.08)" }}>
          <div className="flex items-center justify-between mb-4">
            <div>
              <h3 style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 600, fontSize: 15, color: "var(--navy)" }}>Weekly Attendance Rate</h3>
              <p style={{ fontSize: 12, color: "var(--muted-foreground)" }}>Average across all subjects</p>
            </div>
            <button className="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium"
              style={{ background: "var(--muted)", color: "var(--navy)" }}>
              <Download size={12} /> Export
            </button>
          </div>
          <ResponsiveContainer width="100%" height={200}>
            <LineChart data={weeklyTrend}>
              <CartesianGrid strokeDasharray="3 3" stroke="rgba(13,31,60,0.05)" />
              <XAxis dataKey="name" tick={{ fontSize: 11, fill: "#5a6a85" }} axisLine={false} tickLine={false} />
              <YAxis domain={[80, 100]} tick={{ fontSize: 11, fill: "#5a6a85" }} axisLine={false} tickLine={false} />
              <Tooltip contentStyle={{ borderRadius: 8, fontSize: 12, border: "1px solid rgba(13,31,60,0.1)" }} />
              <Line type="monotone" dataKey="avg" stroke="#c8a951" strokeWidth={2.5} dot={{ fill: "#c8a951", r: 4 }} name="Avg %" />
            </LineChart>
          </ResponsiveContainer>
        </div>

        {/* Grade Performance */}
        <div className="bg-white rounded-2xl p-5 border" style={{ borderColor: "rgba(13,31,60,0.08)" }}>
          <div className="flex items-center justify-between mb-4">
            <div>
              <h3 style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 600, fontSize: 15, color: "var(--navy)" }}>Subject Performance</h3>
              <p style={{ fontSize: 12, color: "var(--muted-foreground)" }}>Average scores per subject</p>
            </div>
            <button className="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium"
              style={{ background: "var(--muted)", color: "var(--navy)" }}>
              <Download size={12} /> Export
            </button>
          </div>
          <ResponsiveContainer width="100%" height={200}>
            <BarChart data={gradeData} barCategoryGap="30%">
              <CartesianGrid strokeDasharray="3 3" stroke="rgba(13,31,60,0.05)" />
              <XAxis dataKey="subject" tick={{ fontSize: 11, fill: "#5a6a85" }} axisLine={false} tickLine={false} />
              <YAxis tick={{ fontSize: 11, fill: "#5a6a85" }} axisLine={false} tickLine={false} />
              <Tooltip contentStyle={{ borderRadius: 8, fontSize: 12, border: "1px solid rgba(13,31,60,0.1)" }} />
              <Bar dataKey="avg" fill="#0d1f3c" radius={[4, 4, 0, 0]} name="Class Avg %" />
              <Bar dataKey="passing" fill="#c8a951" radius={[4, 4, 0, 0]} name="Pass Rate %" />
            </BarChart>
          </ResponsiveContainer>
        </div>
      </div>

      {/* Radar + Table */}
      <div className="grid grid-cols-1 lg:grid-cols-5 gap-4">
        <div className="lg:col-span-2 bg-white rounded-2xl p-5 border" style={{ borderColor: "rgba(13,31,60,0.08)" }}>
          <h3 style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 600, fontSize: 15, color: "var(--navy)", marginBottom: 4 }}>Multi-metric Comparison</h3>
          <p style={{ fontSize: 12, color: "var(--muted-foreground)", marginBottom: 12 }}>Top 3 subjects by enrolled count</p>
          <ResponsiveContainer width="100%" height={220}>
            <RadarChart data={radarData}>
              <PolarGrid stroke="rgba(13,31,60,0.08)" />
              <PolarAngleAxis dataKey="metric" tick={{ fontSize: 11, fill: "#5a6a85" }} />
              <PolarRadiusAxis angle={30} domain={[60, 100]} tick={{ fontSize: 10, fill: "#5a6a85" }} />
              <Radar name="CS101" dataKey="CS101" stroke="#0d1f3c" fill="#0d1f3c" fillOpacity={0.1} />
              <Radar name="CS201" dataKey="CS201" stroke="#c8a951" fill="#c8a951" fillOpacity={0.1} />
              <Radar name="MATH101" dataKey="MATH101" stroke="#3d5a8a" fill="#3d5a8a" fillOpacity={0.1} />
              <Legend iconType="circle" iconSize={8} wrapperStyle={{ fontSize: 11 }} />
            </RadarChart>
          </ResponsiveContainer>
        </div>

        <div className="lg:col-span-3 bg-white rounded-2xl border overflow-hidden" style={{ borderColor: "rgba(13,31,60,0.08)" }}>
          <div className="p-5 border-b" style={{ borderColor: "rgba(13,31,60,0.07)" }}>
            <h3 style={{ fontFamily: "'Outfit', sans-serif", fontWeight: 600, fontSize: 15, color: "var(--navy)" }}>Grade Summary Table</h3>
          </div>
          <div className="overflow-x-auto">
            <table className="w-full">
              <thead>
                <tr style={{ background: "var(--muted)" }}>
                  {["Subject", "Avg Score", "Highest", "Lowest", "Pass Rate"].map((h) => (
                    <th key={h} className="px-4 py-3 text-left" style={{ fontSize: 11, fontWeight: 600, color: "var(--muted-foreground)", textTransform: "uppercase", letterSpacing: "0.05em" }}>{h}</th>
                  ))}
                </tr>
              </thead>
              <tbody>
                {gradeData.map((row, i) => (
                  <tr key={i} className="border-t hover:bg-gray-50/50" style={{ borderColor: "rgba(13,31,60,0.05)" }}>
                    <td className="px-4 py-3" style={{ fontSize: 14, fontWeight: 600, color: "var(--navy)" }}>{row.subject}</td>
                    <td className="px-4 py-3" style={{ fontSize: 13, color: "var(--navy)", fontFamily: "'DM Mono', monospace" }}>{row.avg}%</td>
                    <td className="px-4 py-3" style={{ fontSize: 13, color: "#16a34a", fontFamily: "'DM Mono', monospace", fontWeight: 600 }}>{row.highest}%</td>
                    <td className="px-4 py-3" style={{ fontSize: 13, color: "#d4183d", fontFamily: "'DM Mono', monospace", fontWeight: 600 }}>{row.lowest}%</td>
                    <td className="px-4 py-3">
                      <div className="flex items-center gap-2">
                        <div className="w-16 h-1.5 rounded-full overflow-hidden" style={{ background: "var(--muted)" }}>
                          <div className="h-full rounded-full" style={{ width: `${row.passing}%`, background: row.passing >= 80 ? "#16a34a" : "#ea580c" }} />
                        </div>
                        <span style={{ fontSize: 12, fontWeight: 600, color: "var(--navy)", fontFamily: "'DM Mono', monospace" }}>{row.passing}%</span>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  );
}
