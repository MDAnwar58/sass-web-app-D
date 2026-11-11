export interface Course {
  id: number;
  title: string;
  slug: string;
  description?: string;
  thumbnail?: string;
  price: number;
  level: 'beginner' | 'intermediate' | 'advanced';
  language: string;
  duration_minutes: number;
  status: 'draft' | 'published' | 'archived';
  tags?: string[];
  instructor: { id: number; name: string };
  sections?: Section[];
  enrollments_count?: number;
  lessons_count?: number;
  created_at: string;
}

export interface Section {
  id: number;
  title: string;
  order: number;
  lessons: Lesson[];
}

export interface Lesson {
  id: number;
  title: string;
  type: 'video' | 'pdf' | 'text' | 'quiz';
  content?: string;
  video_url?: string;
  duration_minutes: number;
  is_free_preview: boolean;
  order: number;
  quiz?: Quiz;
}

export interface Quiz {
  id: number;
  title: string;
  pass_mark: number;
  time_limit_minutes?: number;
  questions: QuizQuestion[];
}

export interface QuizQuestion {
  id: number;
  question: string;
  options: string[];
  correct_option: number;
  marks: number;
  order: number;
}

export interface Assignment {
  id: number;
  title: string;
  description?: string;
  due_date?: string;
  total_marks: number;
}

export interface Enrollment {
  id: number;
  user: { id: number; name: string; email: string };
  course: { id: number; title: string };
  status: 'active' | 'completed' | 'cancelled';
  paid_amount: number;
  completed_at?: string;
  created_at: string;
}

export interface Certificate {
  id: number;
  certificate_number: string;
  user: { id: number; name: string };
  course: { id: number; title: string };
  issued_at: string;
}
