import React, { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { motion } from 'framer-motion';
import { Link as RouterLink } from 'react-router-dom';
import { Mail, Phone, MapPin, Send, HelpCircle, ChevronDown, Sparkles, Globe, ShieldCheck, Zap, Award, Users, HeartHandshake, ArrowRight, CheckCircle2 } from 'lucide-react';
import { Card } from '../components/ui/Card';
import { Button } from '../components/ui/Button';
import { SectionDivider } from '../components/ui/SectionDivider';
import { useToastStore } from '../store/toastStore';

// 1. ABOUT US PAGE
export const About = () => {
  const { t } = useTranslation();

  const stats = [
    { label: 'Global Customers', value: '150,000+', icon: Users, color: 'text-amber-500 bg-amber-500/10' },
    { label: 'Satisfaction Rate', value: '99.8%', icon: Award, color: 'text-emerald-500 bg-emerald-500/10' },
    { label: 'Supported Languages', value: '4 Native', icon: Globe, color: 'text-indigo-500 bg-indigo-500/10' },
    { label: 'Quality Guarantee', value: '30-Day', icon: ShieldCheck, color: 'text-rose-500 bg-rose-500/10' }
  ];

  const values = [
    {
      title: 'Native Localization',
      description: 'We eliminate language & currency friction with seamless LTR/RTL layouts and multi-lingual support.',
      icon: Globe,
      color: 'text-blue-500 bg-blue-500/10'
    },
    {
      title: 'Uncompromised Quality',
      description: 'Every product catalog item undergoes strict quality inspections and comes with verified specification sheets.',
      icon: ShieldCheck,
      color: 'text-emerald-500 bg-emerald-500/10'
    },
    {
      title: 'Lightning Performance',
      description: 'Powered by ultra-responsive APIs and modern frontend architectures for zero-wait shopping.',
      icon: Zap,
      color: 'text-amber-500 bg-amber-500/10'
    },
    {
      title: 'Customer-First Heart',
      description: '24/7 dedicated support teams ready to assist you in your native language wherever you are.',
      icon: HeartHandshake,
      color: 'text-rose-500 bg-rose-500/10'
    }
  ];

  const team = [
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
            <span>Established 2026 • Global Commerce</span>
          </motion.div>

          <motion.h1
            initial={{ opacity: 0, y: 25 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.1 }}
            className="text-4xl sm:text-5xl lg:text-6xl font-black font-serif tracking-tight text-[#F5EFE4] leading-tight max-w-4xl mx-auto uppercase"
          >
            Redefining Localized E-Commerce Worldwide
          </motion.h1>

          <motion.p
            initial={{ opacity: 0, y: 25 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.2 }}
            className="text-base sm:text-lg text-white/80 max-w-2xl mx-auto mt-6 leading-relaxed font-sans"
          >
            Nuvara bridges international boutique brands with seamless native shopping experiences—adapting language, culture, and payments automatically.
          </motion.p>
        </div>
      </section>

      {/* 2. Key Stats Grid */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-20">
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
          {stats.map((stat, idx) => {
            const Icon = stat.icon;
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
              Borderlessly Connecting Buyers & Premium Brands
            </h2>
            
            <p className="text-sm sm:text-base text-text-secondary leading-relaxed font-sans">
              Founded with the vision that online shopping should never feel foreign or clunky, Nuvara was engineered from the ground up to support instant multi-locale switching, right-to-left layout perfection, and transparent localized pricing.
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

      {/* 4. Core Values Grid */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-12">
          <span className="text-xs font-bold text-brass uppercase tracking-widest bg-brass/10 border border-brass/20 px-3.5 py-1.5 rounded-full inline-block mb-3">
            Why Choose Us
          </span>
          <h2 className="text-3xl font-bold font-serif text-text-primary uppercase tracking-wide">
            Our Core Principles
          </h2>
          <SectionDivider />
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          {values.map((val, idx) => {
            const Icon = val.icon;
            return (
              <motion.div
                key={idx}
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.4, delay: idx * 0.1 }}
                whileHover={{ y: -6, scale: 1.02 }}
                className="bg-surface border border-border/80 rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group hover:border-accent/40"
              >
                <div>
                  <div className={`w-12 h-12 rounded-xl ${val.color} flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300 shadow-sm`}>
                    <Icon className="w-6 h-6" />
                  </div>
                  <h3 className="text-lg font-bold text-text-primary mb-2 group-hover:text-accent transition-colors">
                    {val.title}
                  </h3>
                  <p className="text-xs text-text-secondary leading-relaxed">
                    {val.description}
                  </p>
                </div>
              </motion.div>
            );
          })}
        </div>
      </section>

      {/* 5. Team Leadership Showcase */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-12">
          <span className="text-xs font-bold text-brass uppercase tracking-widest bg-brass/10 border border-brass/20 px-3.5 py-1.5 rounded-full inline-block mb-3">
            Behind The Brand
          </span>
          <h2 className="text-3xl font-bold font-serif text-text-primary uppercase tracking-wide">
            Meet Our Leadership
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
              className="bg-surface border border-border/80 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 text-left rtl:text-right group"
            >
              <div className="h-64 overflow-hidden relative bg-surface-2">
                <img
                  src={member.image}
                  alt={member.name}
                  className="w-full h-full object-cover group-hover:scale-108 transition-transform duration-700"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent" />
                <div className="absolute bottom-3 left-4 right-4 text-white">
                  <h3 className="text-lg font-bold text-[#F5EFE4]">{member.name}</h3>
                  <p className="text-xs text-amber-300 font-semibold">{member.role}</p>
                </div>
              </div>
              <div className="p-5">
                <p className="text-xs text-text-secondary leading-relaxed italic">
                  "{member.bio}"
                </p>
              </div>
            </motion.div>
          ))}
        </div>
      </section>

      {/* 6. Call To Action Banner */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="relative rounded-3xl overflow-hidden bg-gradient-to-r from-amber-600 via-amber-500 to-yellow-500 p-8 sm:p-12 text-gray-950 shadow-2xl flex flex-col md:flex-row items-center justify-between gap-6">
          <div className="text-left rtl:text-right max-w-xl">
            <h2 className="text-3xl sm:text-4xl font-black font-serif uppercase tracking-tight leading-tight mb-2">
              Ready to Experience Nuvara?
            </h2>
            <p className="text-sm font-medium text-gray-900/90">
              Discover thousands of premium curated products with fast global shipping and 30-day returns.
            </p>
          </div>
          <RouterLink to="/category/electronics">
            <Button
              className="bg-black text-white hover:bg-gray-900 px-8 py-3.5 rounded-xl font-bold uppercase text-xs tracking-wider shadow-xl flex items-center gap-2 flex-shrink-0"
            >
              <span>Explore Collection</span>
              <ArrowRight className="w-4 h-4 rtl-flip" />
            </Button>
          </RouterLink>
        </div>
      </section>
    </div>
  );
};

// 2. CONTACT US PAGE
export const Contact = () => {
  const { addToast } = useToastStore();
  const [formData, setFormData] = useState({ name: '', email: '', subject: 'general', message: '' });
  const [loading, setLoading] = useState(false);

  const handleSubmit = (e) => {
    e.preventDefault();
    if (!formData.name || !formData.email || !formData.message) {
      addToast('Please fill out all required fields', 'danger');
      return;
    }
    setLoading(true);
    setTimeout(() => {
      setLoading(false);
      addToast('Thank you! Your message has been sent successfully.', 'success');
      setFormData({ name: '', email: '', subject: 'general', message: '' });
    }, 1200);
  };

  const contactCards = [
    {
      title: 'Customer Email',
      value: 'support@nuvara.com',
      sub: 'Average response: < 2 hours',
      icon: Mail,
      color: 'text-amber-500 bg-amber-500/10'
    },
    {
      title: 'Phone Support',
      value: '+1 (800) NUVARA',
      sub: 'Toll-free 24/7 dedicated line',
      icon: Phone,
      color: 'text-emerald-500 bg-emerald-500/10'
    },
    {
      title: 'Headquarters',
      value: 'San Francisco, CA',
      sub: '100 Embassy Row, Suite 400',
      icon: MapPin,
      color: 'text-indigo-500 bg-indigo-500/10'
    }
  ];

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
            <span>24/7 Multilingual Support Hub</span>
          </motion.div>

          <motion.h1
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.1 }}
            className="text-4xl sm:text-5xl font-black font-serif tracking-tight text-[#F5EFE4] uppercase"
          >
            Get In Touch With Us
          </motion.h1>

          <motion.p
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.2 }}
            className="text-sm sm:text-base text-white/80 max-w-xl mx-auto mt-4 font-sans leading-relaxed"
          >
            Have a question about an order, localized payments, or custom boutique recommendations? Our global team is here to help anytime.
          </motion.p>
        </div>
      </section>

      {/* Info Cards Grid */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-20">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {contactCards.map((card, idx) => {
            const Icon = card.icon;
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
                  <p className="text-[11px] text-text-secondary mt-0.5 font-medium">
                    {card.sub}
                  </p>
                </div>
              </motion.div>
            );
          })}
        </div>
      </section>

      {/* Form Section */}
      <section className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <motion.div
          initial={{ opacity: 0, y: 25 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.5 }}
          className="bg-surface border border-border/80 rounded-3xl p-8 sm:p-12 shadow-xl relative overflow-hidden"
        >
          <div className="text-center mb-8">
            <h2 className="text-2xl sm:text-3xl font-bold font-serif text-text-primary uppercase">
              Send Us A Message
            </h2>
            <SectionDivider />
          </div>

          <form onSubmit={handleSubmit} className="space-y-6">
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <div>
                <label className="block text-xs font-bold text-text-secondary uppercase tracking-wider mb-2">
                  Your Full Name *
                </label>
                <input
                  type="text"
                  required
                  placeholder="John Doe"
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  className="w-full px-4 py-3 rounded-xl border border-border bg-surface-2/60 text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 transition-all"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-text-secondary uppercase tracking-wider mb-2">
                  Email Address *
                </label>
                <input
                  type="email"
                  required
                  placeholder="john@example.com"
                  value={formData.email}
                  onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                  className="w-full px-4 py-3 rounded-xl border border-border bg-surface-2/60 text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 transition-all"
                />
              </div>
            </div>

            <div>
              <label className="block text-xs font-bold text-text-secondary uppercase tracking-wider mb-2">
                Inquiry Topic
              </label>
              <select
                value={formData.subject}
                onChange={(e) => setFormData({ ...formData, subject: e.target.value })}
                className="w-full px-4 py-3 rounded-xl border border-border bg-surface-2/60 text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent/50 transition-all"
              >
                <option value="general">General Inquiry</option>
                <option value="order">Order & Tracking Issue</option>
                <option value="returns">Returns & Refunds</option>
                <option value="technical">Technical & Language Feedback</option>
              </select>
            </div>

            <div>
              <label className="block text-xs font-bold text-text-secondary uppercase tracking-wider mb-2">
                Your Message *
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

            <Button
              type="submit"
              variant="primary"
              loading={loading}
              icon={Send}
              className="w-full py-4 rounded-xl font-bold uppercase text-xs tracking-wider shadow-lg flex items-center justify-center gap-2"
            >
              Send Message
            </Button>
          </form>
        </motion.div>
      </section>
    </div>
  );
};

// 3. FAQ ACCORDION PAGE
export const FAQ = () => {
  const [searchTerm, setSearchTerm] = useState('');
  const [activeCategory, setActiveCategory] = useState('all');
  const [activeId, setActiveId] = useState(null);

  const categories = [
    { id: 'all', label: 'All Questions' },
    { id: 'shipping', label: 'Shipping & Delivery' },
    { id: 'returns', label: 'Returns & Refunds' },
    { id: 'rtl', label: 'RTL & Language' },
    { id: 'payments', label: 'Payments' }
  ];

  const faqItems = [
    {
      id: 'faq-1',
      category: 'shipping',
      q: 'What shipping options does Nuvara offer worldwide?',
      a: 'We partner with express global carriers (DHL, FedEx, UPS). Express shipping takes 2-4 business days worldwide and is completely free on all orders over $150.'
    },
    {
      id: 'faq-2',
      category: 'returns',
      q: 'How does the 30-day money-back guarantee work?',
      a: 'If you are not 100% satisfied with your item, you can return it in its original packaging within 30 days of delivery. Refunds are processed back to your original payment method within 3 business days of receiving the item.'
    },
    {
      id: 'faq-3',
      category: 'rtl',
      q: 'How do I toggle Right-to-Left (RTL) layout or languages?',
      a: 'Click the Globe icon in the top header navigation bar and select Arabic (العربية). The layout automatically flips to native RTL orientation, adjusts typography, and mirrors all icons instantly.'
    },
    {
      id: 'faq-4',
      category: 'payments',
      q: 'What payment methods are supported?',
      a: 'We accept major international credit/debit cards (Visa, MasterCard, American Express), Apple Pay, Google Pay, as well as Cash on Delivery for select regions.'
    },
    {
      id: 'faq-5',
      category: 'shipping',
      q: 'How can I track my order once shipped?',
      a: 'As soon as your package is dispatched, you will receive a tracking link via email. You can also view live real-time status in your Account Dashboard under "My Orders".'
    },
    {
      id: 'faq-6',
      category: 'returns',
      q: 'Are return shipping labels provided?',
      a: 'Yes! For defective or incorrect items, we provide prepaid return shipping labels. For general preference returns, standard return shipping rates apply.'
    }
  ];

  const handleSearchChange = (e) => {
    const val = e.target.value;
    setSearchTerm(val);
    if (val.trim().length > 0 && activeCategory !== 'all') {
      setActiveCategory('all');
    }
  };

  const filteredFaqs = faqItems.filter(item => {
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
              className={`px-4 py-2 rounded-full text-xs font-bold transition-all whitespace-nowrap ${
                activeCategory === cat.id && !searchTerm
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
          <div className="mb-4 text-xs font-bold text-text-secondary text-center">
            Showing results for "<span className="text-accent">{searchTerm}</span>" ({filteredFaqs.length} found)
          </div>
        )}

        {/* FAQ Accordion List */}
        <div className="space-y-4">
          {filteredFaqs.length > 0 ? (
            filteredFaqs.map((item) => {
              const isSearchActive = searchTerm.trim().length > 0;
              const isOpen = activeId === item.id || isSearchActive;
              return (
                <motion.div
                  key={item.id}
                  layout
                  initial={{ opacity: 0, y: 10 }}
                  animate={{ opacity: 1, y: 0 }}
                  exit={{ opacity: 0, y: -10 }}
                  transition={{ duration: 0.25 }}
                  className="bg-surface border border-border/80 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all"
                >
                  <button
                    onClick={() => toggleAccordion(item.id)}
                    className="w-full px-6 py-5 flex justify-between items-center text-sm font-bold text-text-primary focus:outline-none hover:bg-surface-2/60 transition-colors text-left rtl:text-right"
                  >
                    <span className="pr-4 rtl:pl-4">{item.q}</span>
                    <ChevronDown className={`w-5 h-5 text-accent transition-transform duration-300 flex-shrink-0 ${isOpen ? 'rotate-180' : ''}`} />
                  </button>
                  
                  {isOpen && (
                    <motion.div
                      initial={{ opacity: 0, height: 0 }}
                      animate={{ opacity: 1, height: 'auto' }}
                      transition={{ duration: 0.3 }}
                      className="px-6 pb-5 text-xs sm:text-sm text-text-secondary leading-relaxed border-t border-border/40 pt-4 bg-surface-2/30"
                    >
                      {item.a}
                    </motion.div>
                  )}
                </motion.div>
              );
            })
          ) : (
            <div className="text-center py-12 bg-surface border border-border rounded-2xl p-8">
              <p className="text-sm font-bold text-text-primary mb-1">
                No matching questions found
              </p>
              <p className="text-xs text-text-secondary">
                Try searching for keywords like "shipping", "returns", "RTL", or "payment".
              </p>
            </div>
          )}
        </div>

        {/* Still Have Questions CTA */}
        <div className="mt-16 bg-surface border border-border/80 rounded-3xl p-8 text-center shadow-md">
          <h3 className="text-xl font-bold text-text-primary mb-2">
            Still Have Questions?
          </h3>
          <p className="text-xs text-text-secondary mb-6 max-w-md mx-auto">
            Can't find the answer you're looking for? Please reach out to our dedicated support team.
          </p>
          <RouterLink to="/contact">
            <Button variant="primary" className="px-6 py-3 rounded-xl font-bold uppercase text-xs tracking-wider">
              Contact Support
            </Button>
          </RouterLink>
        </div>
      </section>
    </div>
  );
};
