import React, { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { Mail, Phone, MapPin, Send, HelpCircle, ChevronDown } from 'lucide-react';
import { Card } from '../components/ui/Card';
import { Button } from '../components/ui/Button';
import { useToastStore } from '../store/toastStore';
import { SectionDivider } from '../components/ui/SectionDivider';

// 1. ABOUT US PAGE
export const About = () => {
  return (
    <div className="max-w-4xl mx-auto px-4 py-12 animate-fade-in text-left">
      <div className="text-center mb-8">
        <h1 className="text-3xl font-bold font-display text-text-primary uppercase tracking-wide">
          About Nuvara
        </h1>
        <SectionDivider />
      </div>
      <div className="space-y-6 text-base text-text-secondary leading-relaxed">
        <p className="font-bold text-text-primary text-lg">
          Welcome to Nuvara, your ultimate destination for premium localized shopping.
        </p>
        <p>
          Founded in 2026, Nuvara is built on a simple premise: international e-commerce should feel native. We build high-conversion, highly localized, and lightning-fast digital storefronts that adapt to your language, culture, and preference automatically.
        </p>
        <p>
          Every item in our collection is carefully selected for design excellence and material durability. We bridge the gap between global brands and native shopping experiences by offering full multi-language, multi-currency, and localized payment solutions out of the box.
        </p>
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6">
          <Card>
            <h4 className="font-bold text-text-primary text-sm uppercase tracking-wide mb-2">Our Mission</h4>
            <p className="text-xs">To empower borderless shopping by removing language barriers, layout breakdowns, and payment friction globally.</p>
          </Card>
          <Card>
            <h4 className="font-bold text-text-primary text-sm uppercase tracking-wide mb-2">Our Quality Standard</h4>
            <p className="text-xs">Every product catalog includes verified reviews, explicit specifications sheets, and comes with a 30-day money-back guarantee.</p>
          </Card>
        </div>
      </div>
    </div>
  );
};

// 2. CONTACT US PAGE
export const Contact = () => {
  const { addToast } = useToastStore();
  const [formData, setFormData] = useState({ name: '', email: '', message: '' });
  const [loading, setLoading] = useState(false);

  const handleSubmit = (e) => {
    e.preventDefault();
    if (!formData.name || !formData.email || !formData.message) {
      addToast('Please fill out all fields', 'danger');
      return;
    }
    setLoading(true);
    setTimeout(() => {
      setLoading(false);
      addToast('Message sent! We will contact you soon.', 'success');
      setFormData({ name: '', email: '', message: '' });
    }, 1500);
  };

  return (
    <div className="max-w-6xl mx-auto px-4 py-12 animate-fade-in text-left">
      <div className="text-center mb-8">
        <h1 className="text-3xl font-bold font-display text-text-primary uppercase tracking-wide">
          Contact Us
        </h1>
        <SectionDivider />
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
        {/* Info Column */}
        <div className="space-y-6">
          <Card className="space-y-4">
            <h3 className="font-bold text-text-primary text-sm uppercase">Get In Touch</h3>
            <div className="flex items-center space-x-3 text-xs text-text-secondary">
              <Mail className="w-4 h-4 text-brass flex-shrink-0" />
              <span>support@nuvara.com</span>
            </div>
            <div className="flex items-center space-x-3 text-xs text-text-secondary">
              <Phone className="w-4 h-4 text-brass flex-shrink-0" />
              <span>+1 (800) NUVARA</span>
            </div>
            <div className="flex items-center space-x-3 text-xs text-text-secondary">
              <MapPin className="w-4 h-4 text-brass flex-shrink-0" />
              <span>100 Embassy Row, San Francisco, CA</span>
            </div>
          </Card>
        </div>

        {/* Form Column */}
        <div className="md:col-span-2">
          <Card>
            <h3 className="font-bold text-text-primary text-sm uppercase mb-6">Send A Message</h3>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <label className="block text-xs font-bold text-text-secondary uppercase mb-1.5">Your Name</label>
                <input
                  type="text"
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  className="w-full px-4 py-2.5 rounded-lg border border-border bg-surface-2 text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-green/50"
                />
              </div>
              <div>
                <label className="block text-xs font-bold text-text-secondary uppercase mb-1.5">Email Address</label>
                <input
                  type="email"
                  value={formData.email}
                  onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                  className="w-full px-4 py-2.5 rounded-lg border border-border bg-surface-2 text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-green/50"
                />
              </div>
              <div>
                <label className="block text-xs font-bold text-text-secondary uppercase mb-1.5">Message</label>
                <textarea
                  rows="4"
                  value={formData.message}
                  onChange={(e) => setFormData({ ...formData, message: e.target.value })}
                  className="w-full px-4 py-2.5 rounded-lg border border-border bg-surface-2 text-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-green/50"
                />
              </div>
              
              <Button type="submit" variant="primary" loading={loading} icon={Send}>
                Send Message
              </Button>
            </form>
          </Card>
        </div>
      </div>
    </div>
  );
};

// 3. FAQ ACCORDION PAGE
export const FAQ = () => {
  const faqItems = [
    {
      q: 'What shipping options does Nuvara offer?',
      a: 'We offer express international shipping using DHL and FedEx. Shipping takes 2-4 business days and is free for orders over $150.'
    },
    {
      q: 'How does the 30-day money-back guarantee work?',
      a: 'If you are unsatisfied with your product, return it in original condition and packaging within 30 days. We provide a full refund minus shipping.'
    },
    {
      q: 'How do I toggle RTL layouts or other languages?',
      a: 'Simply click the Globe icon in the Header navigation and select Arabic (العربية). The layout auto-swaps to RTL text flow, flips appropriate arrows, and applies custom Arabic typography.'
    },
    {
      q: 'How do I contact customer support?',
      a: 'You can submit a message on our Contact page, email support@nuvara.com, or dial our toll-free phone number +1 (800) NUVARA.'
    }
  ];

  const [activeIndex, setActiveIndex] = useState(null);

  const toggleAccordion = (idx) => {
    setActiveIndex(activeIndex === idx ? null : idx);
  };

  return (
    <div className="max-w-3xl mx-auto px-4 py-12 animate-fade-in text-left">
      <div className="text-center mb-8">
        <div className="inline-flex items-center justify-center space-x-3 rtl:space-x-reverse mb-2">
          <HelpCircle className="w-8 h-8 text-brass" />
          <h1 className="text-3xl font-bold font-display text-text-primary uppercase tracking-wide">
            FAQ & Help Center
          </h1>
        </div>
        <SectionDivider />
      </div>

      <div className="space-y-4">
        {faqItems.map((item, idx) => {
          const isOpen = activeIndex === idx;
          return (
            <div key={idx} className="border border-border rounded-xl bg-bg-secondary overflow-hidden transition-colors">
              <button
                onClick={() => toggleAccordion(idx)}
                className="w-full px-5 py-4 flex justify-between items-center text-sm font-bold text-text-primary focus:outline-none hover:bg-surface-2 transition-colors text-left rtl:text-right"
              >
                <span>{item.q}</span>
                <ChevronDown className={`w-4 h-4 text-text-secondary transition-transform duration-250 ${isOpen ? 'rotate-180' : ''}`} />
              </button>
              
              {isOpen && (
                <div className="px-5 pb-4 text-xs font-semibold text-text-secondary leading-relaxed border-t border-border/50 pt-3 bg-surface-2/45">
                  {item.a}
                </div>
              )}
            </div>
          );
        })}
      </div>
    </div>
  );
};
