import React, { useState, useEffect } from 'react';
import { useNavigate, useSearchParams } from 'react-router-dom';
import { User, Mail, Lock, Phone, ArrowRight } from 'lucide-react';
import { useLocaleStore } from '../store/localeStore';
import { useToastStore } from '../store/toastStore';
import { useAuthStore } from '../store/authStore';
import { Card } from '../components/ui/Card';
import { Button } from '../components/ui/Button';

const dict = {
  en: {
    login: 'Log In',
    signup: 'Sign Up',
    email: 'Email Address',
    password: 'Password',
    name: 'Full Name',
    phone: 'Phone Number',
    confirmPassword: 'Confirm Password',
    noAccount: "Don't have an account?",
    haveAccount: 'Already have an account?',
    submitting: 'Submitting...',
    required: 'This field is required',
    passwordMismatch: 'Passwords do not match',
    successLogin: 'Logged in successfully!',
    successSignup: 'Account created successfully!',
    errorAuth: 'Authentication failed. Please check your credentials.'
  },
  bn: {
    login: 'লগ ইন',
    signup: 'সাইন আপ',
    email: 'ইমেইল ঠিকানা',
    password: 'পাসওয়ার্ড',
    name: 'পুরো নাম',
    phone: 'ফোন নম্বর',
    confirmPassword: 'পাসওয়ার্ড নিশ্চিত করুন',
    noAccount: 'অ্যাকাউন্ট নেই?',
    haveAccount: 'ইতিমধ্যে একটি অ্যাকাউন্ট আছে?',
    submitting: 'দাখিল করা হচ্ছে...',
    required: 'এই ক্ষেত্রটি পূরণ করা আবশ্যক',
    passwordMismatch: 'পাসওয়ার্ড মেলেনি',
    successLogin: 'সফলভাবে লগইন করা হয়েছে!',
    successSignup: 'অ্যাকাউন্ট সফলভাবে তৈরি করা হয়েছে!',
    errorAuth: 'অনুমোদন ব্যর্থ হয়েছে। আপনার তথ্য পরীক্ষা করুন।'
  },
  es: {
    login: 'Iniciar Sesión',
    signup: 'Registrarse',
    email: 'Correo Electrónico',
    password: 'Contraseña',
    name: 'Nombre Completo',
    phone: 'Número de Teléfono',
    confirmPassword: 'Confirmar Contraseña',
    noAccount: '¿No tienes una cuenta?',
    haveAccount: '¿Ya tienes una cuenta?',
    submitting: 'Enviando...',
    required: 'Este campo es obligatorio',
    passwordMismatch: 'Las contraseñas no coinciden',
    successLogin: '¡Sesión iniciada con éxito!',
    successSignup: '¡Cuenta creada con éxito!',
    errorAuth: 'Fallo de autenticación. Verifique sus credenciales.'
  },
  ar: {
    login: 'تسجيل الدخول',
    signup: 'إنشاء حساب',
    email: 'البريد الإلكتروني',
    password: 'كلمة المرور',
    name: 'الاسم الكامل',
    phone: 'رقم الهاتف',
    confirmPassword: 'تأكيد كلمة المرور',
    noAccount: 'ليس لديك حساب؟',
    haveAccount: 'لديك حساب بالفعل؟',
    submitting: 'جاري التقديم...',
    required: 'هذا الحقل مطلوب',
    passwordMismatch: 'كلمات المرور غير متطابقة',
    successLogin: 'تم تسجيل الدخول بنجاح!',
    successSignup: 'تم إنشاء الحساب بنجاح!',
    errorAuth: 'فشلت عملية المصادقة. يرجى التحقق من بياناتك.'
  }
};

export const Auth = () => {
  const { locale } = useLocaleStore();
  const { addToast } = useToastStore();
  const { login, register, isAuthenticated } = useAuthStore();
  const navigate = useNavigate();
  const [searchParams] = useSearchParams();
  
  const redirectPath = searchParams.get('redirect') || '/dashboard';
  const t = dict[locale] || dict.en;

  const [mode, setMode] = useState('login'); // 'login' | 'signup'
  const [loading, setLoading] = useState(false);
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    password: '',
    confirmPassword: ''
  });
  const [errors, setErrors] = useState({});

  useEffect(() => {
    if (isAuthenticated) {
      navigate(redirectPath, { replace: true });
    }
  }, [isAuthenticated, navigate, redirectPath]);

  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    });
    if (errors[e.target.name]) {
      setErrors({
        ...errors,
        [e.target.name]: ''
      });
    }
  };

  const validateForm = () => {
    const tempErrors = {};
    if (mode === 'signup' && !formData.name.trim()) tempErrors.name = t.required;
    if (!formData.email.trim()) tempErrors.email = t.required;
    if (!formData.password) tempErrors.password = t.required;
    
    if (mode === 'signup') {
      if (formData.password !== formData.confirmPassword) {
        tempErrors.confirmPassword = t.passwordMismatch;
      }
    }
    
    setErrors(tempErrors);
    return Object.keys(tempErrors).length === 0;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!validateForm()) return;

    setLoading(true);
    try {
      if (mode === 'login') {
        await login(formData.email, formData.password);
        addToast(t.successLogin, 'success');
      } else {
        await register(formData.name, formData.email, formData.password, formData.phone);
        addToast(t.successSignup, 'success');
      }
    } catch (err) {
      addToast(err.response?.data?.message || t.errorAuth, 'danger');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="max-w-md mx-auto px-4 py-16 animate-fade-in text-left rtl:text-right">
      <Card className="shadow-lg border-border">
        {/* Toggle Headers */}
        <div className="flex border-b border-border mb-6">
          <button
            onClick={() => { setMode('login'); setErrors({}); }}
            className={`flex-1 pb-3 text-center text-sm font-bold border-b-2 transition-all ${
              mode === 'login'
                ? 'border-accent text-accent'
                : 'border-transparent text-text-secondary hover:text-text-primary'
            }`}
          >
            {t.login}
          </button>
          <button
            onClick={() => { setMode('signup'); setErrors({}); }}
            className={`flex-1 pb-3 text-center text-sm font-bold border-b-2 transition-all ${
              mode === 'signup'
                ? 'border-accent text-accent'
                : 'border-transparent text-text-secondary hover:text-text-primary'
            }`}
          >
            {t.signup}
          </button>
        </div>

        <form onSubmit={handleSubmit} className="space-y-4">
          {mode === 'signup' && (
            <div>
              <label className="block text-xs font-bold text-text-secondary uppercase mb-1.5">{t.name}</label>
              <div className="relative">
                <input
                  type="text"
                  name="name"
                  value={formData.name}
                  onChange={handleChange}
                  className={`w-full pl-10 pr-4 rtl:pr-10 rtl:pl-4 py-2.5 rounded-lg border bg-bg-primary text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 ${
                    errors.name ? 'border-red-500' : 'border-border'
                  }`}
                />
                <User className="absolute left-3.5 rtl:right-3.5 rtl:left-auto top-3.5 w-4 h-4 text-text-secondary" />
              </div>
              {errors.name && <p className="text-xs text-red-500 mt-1">{errors.name}</p>}
            </div>
          )}

          <div>
            <label className="block text-xs font-bold text-text-secondary uppercase mb-1.5">{t.email}</label>
            <div className="relative">
              <input
                type="email"
                name="email"
                value={formData.email}
                onChange={handleChange}
                className={`w-full pl-10 pr-4 rtl:pr-10 rtl:pl-4 py-2.5 rounded-lg border bg-bg-primary text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 ${
                  errors.email ? 'border-red-500' : 'border-border'
                }`}
              />
              <Mail className="absolute left-3.5 rtl:right-3.5 rtl:left-auto top-3.5 w-4 h-4 text-text-secondary" />
            </div>
            {errors.email && <p className="text-xs text-red-500 mt-1">{errors.email}</p>}
          </div>

          {mode === 'signup' && (
            <div>
              <label className="block text-xs font-bold text-text-secondary uppercase mb-1.5">{t.phone}</label>
              <div className="relative">
                <input
                  type="text"
                  name="phone"
                  value={formData.phone}
                  onChange={handleChange}
                  className="w-full pl-10 pr-4 rtl:pr-10 rtl:pl-4 py-2.5 rounded-lg border border-border bg-bg-primary text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent/50"
                />
                <Phone className="absolute left-3.5 rtl:right-3.5 rtl:left-auto top-3.5 w-4 h-4 text-text-secondary" />
              </div>
            </div>
          )}

          <div>
            <label className="block text-xs font-bold text-text-secondary uppercase mb-1.5">{t.password}</label>
            <div className="relative">
              <input
                type="password"
                name="password"
                value={formData.password}
                onChange={handleChange}
                className={`w-full pl-10 pr-4 rtl:pr-10 rtl:pl-4 py-2.5 rounded-lg border bg-bg-primary text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 ${
                  errors.password ? 'border-red-500' : 'border-border'
                }`}
              />
              <Lock className="absolute left-3.5 rtl:right-3.5 rtl:left-auto top-3.5 w-4 h-4 text-text-secondary" />
            </div>
            {errors.password && <p className="text-xs text-red-500 mt-1">{errors.password}</p>}
          </div>

          {mode === 'signup' && (
            <div>
              <label className="block text-xs font-bold text-text-secondary uppercase mb-1.5">{t.confirmPassword}</label>
              <div className="relative">
                <input
                  type="password"
                  name="confirmPassword"
                  value={formData.confirmPassword}
                  onChange={handleChange}
                  className={`w-full pl-10 pr-4 rtl:pr-10 rtl:pl-4 py-2.5 rounded-lg border bg-bg-primary text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 ${
                    errors.confirmPassword ? 'border-red-500' : 'border-border'
                  }`}
                />
                <Lock className="absolute left-3.5 rtl:right-3.5 rtl:left-auto top-3.5 w-4 h-4 text-text-secondary" />
              </div>
              {errors.confirmPassword && <p className="text-xs text-red-500 mt-1">{errors.confirmPassword}</p>}
            </div>
          )}

          <div className="pt-4">
            <Button
              type="submit"
              variant="primary"
              loading={loading}
              className="w-full py-3 text-sm font-bold uppercase tracking-wider bg-accent text-white"
              icon={ArrowRight}
            >
              {mode === 'login' ? t.login : t.signup}
            </Button>
          </div>
        </form>

        {/* Form Mode Switcher Footer */}
        <div className="mt-6 text-center text-xs text-text-secondary font-medium">
          {mode === 'login' ? (
            <p>
              {t.noAccount}{' '}
              <button
                onClick={() => { setMode('signup'); setErrors({}); }}
                className="text-accent font-bold hover:underline focus:outline-none"
              >
                {t.signup}
              </button>
            </p>
          ) : (
            <p>
              {t.haveAccount}{' '}
              <button
                onClick={() => { setMode('login'); setErrors({}); }}
                className="text-accent font-bold hover:underline focus:outline-none"
              >
                {t.login}
              </button>
            </p>
          )}
        </div>
      </Card>
    </div>
  );
};
