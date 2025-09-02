📚Learn Hub– Community Learning Portal
________________________________________
1. Main Purpose
A lightweight online platform where:
•	Teachers upload learning materials (PDFs, videos, notes).
•	Students access, download, and study them.
•	AI generates quizzes, assists students with explanations, and gives teachers insights.
•	Gamification keeps students engaged (points, badges, leaderboards).
________________________________________
2. Core Features
For Students
•	Account creation & login.
•	Browse subjects (Math, Science, Languages, etc.).
•	View or download materials.
•	Take quizzes (manual or AI-generated) → instant results.
•	Gamification: earn points, badges, and leaderboard rank.
•	Personalized learning recommendations.
For Teachers
•	Account creation & login.
•	Upload materials (PDFs, videos, notes).
•	Create quizzes manually OR auto-generate with AI.
•	Track how many students accessed their materials.
•	AI insights: “Most students struggled with topic X.”
For Admin
•	Manage users (add, suspend, assign roles).
•	Approve/remove uploaded content.
•	Analytics dashboard (most viewed subjects, active students, top teachers).
________________________________________
3. System Workflow
1.	Student registers → gets access to subjects.
2.	Teacher uploads learning material.
3.	AI scans content → suggests quiz questions.
4.	Admin approves (optional).
5.	Student views/downloads material.
6.	Student asks AI Tutor for clarification.
7.	Student takes quiz → result stored in DB.
8.	Gamification system updates points & badges.
9.	AI analyzes results → teacher sees insights.
________________________________________
4. Database Structure (MySQL)
Users
•	id, name, email, password, role (student/teacher/admin)
Subjects
•	id, subject_name
Materials
•	id, title, description, file_url, subject_id, uploaded_by
Quizzes
•	id, material_id, question, options, correct_answer, source (manual/AI)
Results
•	id, user_id, quiz_id, score, date
Points
•	id, user_id, total_points, badge
AI_Logs
•	id, user_id, query, AI_response, date
________________________________________
5. Tech Stack
•	Frontend :
o	HTML files with inline CSS and javascript 
•	Backend:
o	PHP
•	Database:
o	MySQL (traditional)
•	AI Layer:
o	Hugging Face models (optional, free/local)
________________________________________
6. Extra Features
•	Search bar (AI-powered semantic search).
•	Offline mode (download PDF for later).
•	Leaderboard → ranks top students.
•	Voice assistant → reads materials aloud.
________________________________________
⚡ Example Use Case
1.	Teacher uploads “Introduction to Biology.pdf”.
2.	AI generates 10 quiz questions. Teacher reviews & approves.
3.	Student logs in → reads PDF → takes quiz.
4.	Student scores 8/10 → earns 20 points + “Biology Starter Badge.”
5.	Student asks AI Tutor: “Explain photosynthesis simply.”
6.	Teacher sees analytics: “70% of students failed question 5 → revisit chlorophyll.”

________________________________________
7. Initial accounts
   Role    Email                      Password
. Admin    admin@learnhub.com         admin123

. Teacher  teacher@learnhub.com       teacher123

. Student  student@learnhub.com       student123



