import React, { useState, useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import { motion } from 'framer-motion';
import { Link as RouterLink } from 'react-router-dom';
import { Mail, Phone, MapPin, Send, HelpCircle, ChevronDown, Sparkles, Globe, ShieldCheck, Zap, Award, Users, HeartHandshake, ArrowRight, CheckCircle2 } from 'lucide-react';
import { Card } from '../components/ui/Card';
import { Button } from '../components/ui/Button';
import { SectionDivider } from '../components/ui/SectionDivider';
import { useToastStore } from '../store/toastStore';
import { useLocaleStore } from '../store/localeStore';

const API_BASE_URL = 'http://127.0.0.1:8000/api/v1';

const ICON_MAP = {
  Users: Users,
  Award: Award,
  Globe: Globe,
  ShieldCheck: ShieldCheck,
  Zap: Zap,
  HeartHandshake: HeartHandshake,
  Mail: Mail,
  Phone: Phone,
  MapPin: MapPin,
  Sparkles: Sparkles
};

// 1. ABOUT US PAGE
export const About = () => {
  const { t } = useTranslation();
  const { locale } = useLocaleStore();

  const [aboutData, setAboutData] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch(`${API_BASE_URL}/pages/about`, {
      headers: {
        'Accept-Language': locale || 'en'
      }
    })
      .then(res => res.json())
      .then(data => {
        if (!data.error) {
          setAboutData(data);
        }
      })
      .catch(err => console.error('Failed to load dynamic About Us data:', err))
      .finally(() => setLoading(false));
  }, [locale]);

  const defaultStats = [
    { label: 'Global Customers', value: '150,000+', icon: 'Users', color: 'text-amber-500 bg-amber-500/10' },
    { label: 'Satisfaction Rate', value: '99.8%', icon: 'Award', color: 'text-emerald-500 bg-emerald-500/10' },
    { label: 'Supported Languages', value: '4 Native', icon: 'Globe', color: 'text-indigo-500 bg-indigo-500/10' },
    { label: 'Quality Guarantee', value: '30-Day', icon: 'ShieldCheck', color: 'text-rose-500 bg-rose-500/10' }
  ];

  const defaultTeam = [
    {
      name: 'Elena Vance',
      role: 'Founder & CEO',
      image: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&auto=format&fit=crop&q=80',
      bio: 'Pioneering global digital commerce with a focus on native user experiences.'
    },
    {
      name: 'Marcus Chen',
      role: 'Head of Product Design',
      image: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&auto=format&fit=crop&q=80',
      bio: 'Crafting minimalist, intuitive interfaces tailored for diverse worldwide cultures.'
    },
    {
      name: 'Aisha Al-Mansoor',
      role: 'Chief Technology Officer',
      image: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&auto=format&fit=crop&q=80',
      bio: 'Building ultra-resilient, lightning-fast architecture for international scale.'
    }
  ];

  const stats = aboutData?.stats || defaultStats;
  const team = aboutData?.team || defaultTeam;
  const heroBadge = aboutData?.hero_badge || 'Established 2026 • Global Commerce';
  const heroTitle = aboutData?.hero_title || 'Redefining Localized E-Commerce Worldwide';
  const heroSub = aboutData?.hero_subtitle || 'Nuvara bridges international boutique brands with seamless native shopping experiences—adapting language, culture, and payments automatically.';
  const storyTitle = aboutData?.story_title || 'Borderlessly Connecting Buyers & Premium Brands';
  const storyBody = aboutData?.story_body || 'Founded with the vision that online shopping should never feel foreign or clunky, Nuvara was engineered from the ground up to support instant multi-locale switching, right-to-left layout perfection, and transparent localized pricing.';

  return (
    <div className="space-y-20 pb-20 animate-fade-in text-left rtl:text-right">

      {/* 1. Hero Header Banner */}
      <section className="relative w-full bg-gradient-to-r from-gray-900 via-stone-900 to-amber-950 py-20 md:py-28 text-white overflow-hidden shadow-xl">
        <div className="absolute inset-0 opacity-20 pointer-events-none bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:16px_16px]" />

        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
          <motion.div
            initial={{ opacity: 0, y: 25 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6 }}
            className="inline-flex items-center space-x-2 rtl:space-x-reverse bg-white/10 backdrop-blur-md px-4 py-1.5 rounded-full border border-white/20 text-xs font-bold uppercase tracking-widest text-amber-300 mb-6"
          >
            <Sparkles className="w-4 h-4 text-amber-400" />
            <span>{heroBadge}</span>
          </motion.div>

          <motion.h1
            initial={{ opacity: 0, y: 25 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.1 }}
            className="text-4xl sm:text-5xl lg:text-6xl font-black font-serif tracking-tight text-[#F5EFE4] leading-tight max-w-4xl mx-auto uppercase"
          >
            {heroTitle}
          </motion.h1>

          <motion.p
            initial={{ opacity: 0, y: 25 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.2 }}
            className="text-base sm:text-lg text-white/80 max-w-2xl mx-auto mt-6 leading-relaxed font-sans"
          >
            {heroSub}
          </motion.p>
        </div>
      </section>

      {/* 2. Key Stats Grid */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-20">
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
          {stats.map((stat, idx) => {
            const Icon = ICON_MAP[stat.icon] || Users;
            return (
              <motion.div
                key={idx}
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.4, delay: idx * 0.1 }}
                whileHover={{ y: -5, scale: 1.02 }}
                className="bg-surface border border-border/80 rounded-2xl p-6 shadow-lg backdrop-blur-md hover:shadow-xl transition-all duration-300 text-center"
              >
                <div className={`w-12 h-12 rounded-xl ${stat.color} flex items-center justify-center mx-auto mb-4 shadow-sm`}>
                  <Icon className="w-6 h-6" />
                </div>
                <h3 className="text-2xl sm:text-3xl font-black font-display text-text-primary">
                  {stat.value}
                </h3>
                <p className="text-xs text-text-secondary font-bold uppercase tracking-wider mt-1">
                  {stat.label}
                </p>
              </motion.div>
            );
          })}
        </div>
      </section>

      {/* 3. Our Brand Story & Mission */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

          {/* Image Showcase Collage */}
          <motion.div
            initial={{ opacity: 0, x: -30 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6 }}
            className="lg:col-span-6 relative"
          >
            <div className="relative rounded-3xl overflow-hidden shadow-2xl border-4 border-surface group">
              <img
                src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=900&auto=format&fit=crop&q=80"
                alt="Nuvara Boutique Showcase"
                className="w-full h-[420px] object-cover group-hover:scale-105 transition-transform duration-700"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent" />
              <div className="absolute bottom-6 left-6 right-6 text-white">
                <span className="text-xs font-extrabold uppercase tracking-widest text-amber-300 bg-black/40 backdrop-blur-md px-3 py-1 rounded-full border border-white/20">
                  Crafted For Excellence
                </span>
                <h3 className="text-xl font-bold mt-2 text-[#F5EFE4]">
                  Where International Elegance Meets Native Comfort
                </h3>
              </div>
            </div>
          </motion.div>

          {/* Text Story Column */}
          <motion.div
            initial={{ opacity: 0, x: 30 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6 }}
            className="lg:col-span-6 space-y-6"
          >
            <span className="text-xs font-bold text-brass uppercase tracking-widest bg-brass/10 border border-brass/20 px-3.5 py-1.5 rounded-full inline-block">
              Our Vision
            </span>
            <h2 className="text-3xl sm:text-4xl font-bold font-serif text-text-primary tracking-tight leading-tight">
              {storyTitle}
            </h2>

            <p className="text-sm sm:text-base text-text-secondary leading-relaxed font-sans">
              {storyBody}
            </p>

            <div className="space-y-3 pt-2">
              {[
                '100% Native RTL & LTR Language Support',
                'Curated 15-Point Product Quality Standard',
                'Fast Global Dispatch with Trackable Shipping',
                'Dedicated 24/7 Multilingual Support Hub'
              ].map((item, idx) => (
                <div key={idx} className="flex items-center space-x-3 rtl:space-x-reverse text-sm font-bold text-text-primary">
                  <CheckCircle2 className="w-5 h-5 text-emerald-500 flex-shrink-0" />
                  <span>{item}</span>
                </div>
              ))}
            </div>
          </motion.div>
        </div>
      </section>

      {/* 4. Team Leadership Showcase */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-12">
          <span className="text-xs font-bold text-brass uppercase tracking-widest bg-brass/10 border border-brass/20 px-3.5 py-1.5 rounded-full inline-block mb-3">
            Behind The Brand
          </span>
          <h2 className="text-3xl font-bold font-serif text-text-primary uppercase tracking-wide">
            Leadership Team
          </h2>
          <SectionDivider />
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {team.map((member, idx) => (
            <motion.div
              key={idx}
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.4, delay: idx * 0.1 }}
              whileHover={{ y: -6 }}
              className="bg-surface border border-border/80 rounded-2xl overflow-hidden shadow-lg group"
            >
              <div className="h-64 overflow-hidden relative">
                <img
                  src={member.image}
                  alt={member.name}
                  className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-80" />
                <div className="absolute bottom-4 left-4 right-4 text-white">
                  <h3 className="text-xl font-bold text-[#F5EFE4]">{member.name}</h3>
                  <p className="text-xs font-bold text-brass uppercase tracking-wider mt-0.5">{member.role}</p>
                </div>
              </div>
              <div className="p-6">
                <p className="text-xs text-text-secondary leading-relaxed italic">
                  "{member.bio}"
                </p>
              </div>
            </motion.div>
          ))}
        </div>
      </section>

    </div>
  );
};

// 2. CONTACT PAGE
export const Contact = () => {
  const { t } = useTranslation();
  const { locale } = useLocaleStore();
  const { addToast } = useToastStore();

  const [contactData, setContactData] = useState(null);

  useEffect(() => {
    fetch(`${API_BASE_URL}/pages/contact`, {
      headers: {
        'Accept-Language': locale || 'en'
      }
    })
      .then(res => res.json())
      .then(data => {
        if (!data.error) {
          setContactData(data);
        }
      })
      .catch(err => console.error('Failed to load dynamic Contact data:', err));
  }, [locale]);

  const [formData, setFormData] = useState({
    name: '',
    email: '',
    subject: '',
    message: ''
  });
  const [submitted, setSubmitted] = useState(false);

  const handleSubmit = (e) => {
    e.preventDefault();
    setSubmitted(true);
    addToast(t('contact.form_success') || 'Thank you! Your message has been received.', 'success');
  };

  const defaultCards = [
    {
      title: 'Customer Support',
      value: 'support@nuvara.com',
      sub: 'Response within 2 hours',
      icon: 'Mail',
      color: 'text-blue-500 bg-blue-500/10'
    },
    {
      title: 'Direct Hotline',
      value: '+1 (800) 555-NUVARA',
      sub: 'Mon - Sun, 24/7 Hotline',
      icon: 'Phone',
      color: 'text-emerald-500 bg-emerald-500/10'
    },
    {
      title: 'Headquarters',
      value: 'San Francisco, CA',
      sub: '100 Embassy Row, Suite 400',
      icon: 'MapPin',
      color: 'text-indigo-500 bg-indigo-500/10'
    }
  ];

  const cards = contactData?.cards || defaultCards;
  const heroBadge = contactData?.hero_badge || '24/7 Multilingual Support Hub';
  const heroTitle = contactData?.hero_title || 'Get In Touch With Us';
  const heroSub = contactData?.hero_subtitle || 'Have a question about an order, localized payments, or custom boutique recommendations? Our global team is here to help anytime.';

  return (
    <div className="space-y-16 pb-20 animate-fade-in text-left rtl:text-right">

      {/* Hero Header */}
      <section className="relative w-full bg-gradient-to-r from-gray-900 via-stone-900 to-amber-950 py-16 md:py-24 text-white overflow-hidden shadow-xl">
        <div className="absolute inset-0 opacity-20 pointer-events-none bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:16px_16px]" />

        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5 }}
            className="inline-flex items-center space-x-2 rtl:space-x-reverse bg-white/10 backdrop-blur-md px-4 py-1.5 rounded-full border border-white/20 text-xs font-bold uppercase tracking-widest text-amber-300 mb-4"
          >
            <Sparkles className="w-4 h-4 text-amber-400" />
            <span>{heroBadge}</span>
          </motion.div>

          <motion.h1
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.1 }}
            className="text-4xl sm:text-5xl font-black font-serif tracking-tight text-[#F5EFE4] uppercase"
          >
            {heroTitle}
          </motion.h1>

          <motion.p
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.2 }}
            className="text-sm sm:text-base text-white/80 max-w-xl mx-auto mt-4 font-sans leading-relaxed"
          >
            {heroSub}
          </motion.p>
        </div>
      </section>

      {/* Info Cards Grid */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-20">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {cards.map((card, idx) => {
            const Icon = ICON_MAP[card.icon] || Mail;
            return (
              <motion.div
                key={idx}
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.4, delay: idx * 0.1 }}
                whileHover={{ y: -5, scale: 1.02 }}
                className="bg-surface border border-border/80 rounded-2xl p-6 shadow-lg backdrop-blur-md hover:shadow-xl transition-all duration-300 flex items-center space-x-4 rtl:space-x-reverse"
              >
                <div className={`w-12 h-12 rounded-xl ${card.color} flex items-center justify-center flex-shrink-0 shadow-sm`}>
                  <Icon className="w-6 h-6" />
                </div>
                <div>
                  <h3 className="text-xs font-bold text-text-secondary uppercase tracking-wider">
                    {card.title}
                  </h3>
                  <p className="text-base font-bold text-text-primary mt-0.5">
                    {card.value}
                  </p>
                  <p className="text-[11px] text-text-secondary mt-0.5">
                    {card.sub}
                  </p>
                </div>
              </motion.div>
            );
          })}
        </div>
      </section>

      {/* Interactive Form Section */}
      <section className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="bg-surface border border-border/80 rounded-3xl p-8 md:p-12 shadow-xl relative overflow-hidden">
          <div className="text-center mb-8">
            <h2 className="text-2xl sm:text-3xl font-bold font-serif text-text-primary uppercase tracking-tight">
              Send Us A Message
            </h2>
            <p className="text-xs sm:text-sm text-text-secondary mt-2">
              Fill out the form below and our multilingual support hub will get back to you promptly.
            </p>
          </div>

          {submitted ? (
            <motion.div
              initial={{ opacity: 0, scale: 0.95 }}
              animate={{ opacity: 1, scale: 1 }}
              className="text-center py-12 space-y-4"
            >
              <div className="w-16 h-16 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 rounded-full flex items-center justify-center mx-auto">
                <CheckCircle2 className="w-8 h-8" />
              </div>
              <h3 className="text-xl font-bold text-text-primary">
                Message Sent Successfully!
              </h3>
              <p className="text-xs sm:text-sm text-text-secondary max-w-md mx-auto">
                Thank you for contacting Nuvara. An automated confirmation email has been dispatched to your address.
              </p>
              <Button onClick={() => setSubmitted(false)} variant="outline" className="mt-4">
                Send Another Message
              </Button>
            </motion.div>
          ) : (
            <form onSubmit={handleSubmit} className="space-y-6">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label className="block text-xs font-bold text-text-secondary uppercase tracking-wider mb-2">
                    Your Name
                  </label>
                  <input
                    type="text"
                    required
                    placeholder="Your Name"
                    value={formData.name}
                    onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                    className="w-full px-4 py-3 rounded-xl border border-border bg-surface-2/60 text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 transition-all"
                  />
                </div>

                <div>
                  <label className="block text-xs font-bold text-text-secondary uppercase tracking-wider mb-2">
                    Email Address
                  </label>
                  <input
                    type="email"
                    required
                    placeholder="Your Email"
                    value={formData.email}
                    onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                    className="w-full px-4 py-3 rounded-xl border border-border bg-surface-2/60 text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 transition-all"
                  />
                </div>
              </div>

              <div>
                <label className="block text-xs font-bold text-text-secondary uppercase tracking-wider mb-2">
                  Subject
                </label>
                <input
                  type="text"
                  required
                  placeholder="Order inquiry, shipping request, etc..."
                  value={formData.subject}
                  onChange={(e) => setFormData({ ...formData, subject: e.target.value })}
                  className="w-full px-4 py-3 rounded-xl border border-border bg-surface-2/60 text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 transition-all"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-text-secondary uppercase tracking-wider mb-2">
                  Your Message
                </label>
                <textarea
                  rows="5"
                  required
                  placeholder="How can we help you today?"
                  value={formData.message}
                  onChange={(e) => setFormData({ ...formData, message: e.target.value })}
                  className="w-full px-4 py-3 rounded-xl border border-border bg-surface-2/60 text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 transition-all"
                />
              </div>

              <div className="flex justify-end">
                <Button type="submit" variant="primary" size="lg" className="w-full sm:w-auto px-8">
                  <Send className="w-4 h-4 mr-2 rtl:ml-2 rtl:mr-0" />
                  <span>Send Message</span>
                </Button>
              </div>
            </form>
          )}
        </div>
      </section>

    </div>
  );
};

// 3. FAQ PAGE
export const Faq = () => {
  const { t } = useTranslation();
  const { locale } = useLocaleStore();

  const [faqsList, setFaqsList] = useState([]);
  const [activeCategory, setActiveCategory] = useState('all');
  const [searchTerm, setSearchTerm] = useState('');
  const [activeId, setActiveId] = useState(null);

  useEffect(() => {
    fetch(`${API_BASE_URL}/pages/faq`, {
      headers: {
        'Accept-Language': locale || 'en'
      }
    })
      .then(res => res.json())
      .then(data => {
        if (Array.isArray(data)) {
          setFaqsList(data);
        }
      })
      .catch(err => console.error('Failed to load dynamic FAQs:', err));
  }, [locale]);

  const categories = [
    { id: 'all', label: 'All Questions' },
    { id: 'general', label: 'General' },
    { id: 'shipping', label: 'Orders & Shipping' },
    { id: 'returns', label: 'Returns & Refunds' },
    { id: 'payment', label: 'Payments' }
  ];

  const handleSearchChange = (e) => {
    setSearchTerm(e.target.value);
  };

  const filteredFaqs = faqsList.filter(item => {
    const query = searchTerm.toLowerCase().trim();
    if (!query) {
      return activeCategory === 'all' || item.category === activeCategory;
    }
    const inQuestion = item.q.toLowerCase().includes(query);
    const inAnswer = item.a.toLowerCase().includes(query);
    return inQuestion || inAnswer;
  });

  const toggleAccordion = (id) => {
    setActiveId(activeId === id ? null : id);
  };

  return (
    <div className="space-y-16 pb-20 animate-fade-in text-left rtl:text-right">

      {/* Hero Header */}
      <section className="relative w-full bg-gradient-to-r from-gray-900 via-stone-900 to-amber-950 py-16 md:py-24 text-white overflow-hidden shadow-xl">
        <div className="absolute inset-0 opacity-20 pointer-events-none bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:16px_16px]" />

        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5 }}
            className="inline-flex items-center space-x-2 rtl:space-x-reverse bg-white/10 backdrop-blur-md px-4 py-1.5 rounded-full border border-white/20 text-xs font-bold uppercase tracking-widest text-amber-300 mb-4"
          >
            <HelpCircle className="w-4 h-4 text-amber-400" />
            <span>Help Center & Knowledge Base</span>
          </motion.div>

          <motion.h1
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.1 }}
            className="text-4xl sm:text-5xl font-black font-serif tracking-tight text-[#F5EFE4] uppercase"
          >
            Frequently Asked Questions
          </motion.h1>

          {/* Live Search Box */}
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.2 }}
            className="max-w-xl mx-auto mt-8 relative"
          >
            <input
              type="text"
              placeholder="Type to search FAQ (e.g. shipping, returns, RTL, payment)..."
              value={searchTerm}
              onChange={handleSearchChange}
              className="w-full px-5 py-3.5 pl-12 rtl:pr-12 rounded-2xl bg-white/15 backdrop-blur-md border border-white/30 text-white placeholder-white/70 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 transition-all shadow-inner"
            />
            <HelpCircle className="w-5 h-5 text-amber-300 absolute left-4 rtl:right-4 top-4 pointer-events-none" />
          </motion.div>
        </div>
      </section>

      {/* Main Container */}
      <section className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {/* Category Tabs */}
        <div className="flex items-center justify-center space-x-2 rtl:space-x-reverse overflow-x-auto pb-4 mb-8">
          {categories.map(cat => (
            <button
              key={cat.id}
              onClick={() => {
                setActiveCategory(cat.id);
                setSearchTerm('');
              }}
              className={`px-4 py-2 rounded-full text-xs font-bold transition-all whitespace-nowrap ${activeCategory === cat.id && !searchTerm
                  ? 'bg-accent text-white shadow-md'
                  : 'bg-surface border border-border text-text-secondary hover:text-text-primary'
                }`}
            >
              {cat.label}
            </button>
          ))}
        </div>

        {/* Live Search Results Status */}
        {searchTerm.trim().length > 0 && (
          <p className="text-xs font-bold text-text-secondary mb-4">
            Found {filteredFaqs.length} results matching "{searchTerm}"
          </p>
        )}

        {/* Accordion FAQ List */}
        <div className="space-y-4">
          {filteredFaqs.length === 0 ? (
            <div className="text-center py-12 bg-surface border border-border rounded-2xl">
              <HelpCircle className="w-10 h-10 text-text-secondary mx-auto mb-3 opacity-50" />
              <p className="text-sm font-bold text-text-primary">No FAQ entries found</p>
              <p className="text-xs text-text-secondary mt-1">Try refining your search keyword or switching category tabs.</p>
            </div>
          ) : (
            filteredFaqs.map(item => {
              const isOpen = activeId === item.id;
              return (
                <div
                  key={item.id}
                  className="bg-surface border border-border/80 rounded-2xl overflow-hidden shadow-xs hover:border-accent/30 transition-all duration-200"
                >
                  <button
                    onClick={() => toggleAccordion(item.id)}
                    className="w-full px-6 py-4 flex items-center justify-between text-left rtl:text-right font-bold text-sm sm:text-base text-text-primary hover:text-accent transition-colors"
                  >
                    <span className="pr-4 rtl:pl-4">{item.q}</span>
                    <ChevronDown className={`w-5 h-5 text-accent transition-transform duration-300 flex-shrink-0 ${isOpen ? 'rotate-180' : ''}`} />
                  </button>

                  {isOpen && (
                    <motion.div
                      initial={{ opacity: 0, height: 0 }}
                      animate={{ opacity: 1, height: 'auto' }}
                      exit={{ opacity: 0, height: 0 }}
                      className="px-6 pb-5 pt-1 border-t border-border/40 text-xs sm:text-sm text-text-secondary leading-relaxed font-sans"
                    >
                      {item.a}
                    </motion.div>
                  )}
                </div>
              );
            })
          )}
        </div>
      </section>

    </div>
  );
};

export const FAQ = Faq;

