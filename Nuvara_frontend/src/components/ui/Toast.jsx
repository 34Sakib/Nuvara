import React from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { CheckCircle, AlertCircle, Info, X } from 'lucide-react';
import { useToastStore } from '../../store/toastStore';

export const Toast = ({ toast }) => {
  const { removeToast } = useToastStore();
  
  const toastStyles = {
    success: {
      border: 'border-green-500/30 dark:border-green-500/20',
      bg: 'bg-surface',
      icon: <CheckCircle className="w-5 h-5 text-green-600 dark:text-green-400" />
    },
    danger: {
      border: 'border-red-500/30 dark:border-red-500/20',
      bg: 'bg-surface',
      icon: <AlertCircle className="w-5 h-5 text-red-600 dark:text-red-400" />
    },
    info: {
      border: 'border-indigo-500/30 dark:border-indigo-500/20',
      bg: 'bg-surface',
      icon: <Info className="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
    }
  };

  const style = toastStyles[toast.type] || toastStyles.info;

  return (
    <motion.div
      layout
      initial={{ opacity: 0, y: -20, scale: 0.9 }}
      animate={{ opacity: 1, y: 0, scale: 1 }}
      exit={{ opacity: 0, y: -20, scale: 0.9 }}
      className={`flex items-center justify-between p-4 mb-3 border rounded-xl shadow-xl w-80 max-w-full ${style.bg} ${style.border}`}
    >
      <div className="flex items-center space-x-3 rtl:space-x-reverse">
        {style.icon}
        <p className="text-sm font-semibold text-text-primary">{toast.message}</p>
      </div>
      <button
        onClick={() => removeToast(toast.id)}
        className="text-text-secondary hover:text-text-primary p-0.5 rounded transition-colors"
      >
        <X className="w-4 h-4" />
      </button>
    </motion.div>
  );
};

export const ToastContainer = () => {
  const { toasts } = useToastStore();

  return (
    <div className="fixed top-6 right-6 rtl:left-6 rtl:right-auto z-50 flex flex-col items-end rtl:items-start max-w-[calc(100vw-3rem)]">
      <AnimatePresence>
        {toasts.map((toast) => (
          <Toast key={toast.id} toast={toast} />
        ))}
      </AnimatePresence>
    </div>
  );
};
