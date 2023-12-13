import React, { ReactNode } from 'react';
import { motion } from 'framer-motion';

export const StepAnim = ({ children }: { children: ReactNode }) => (
  <motion.div
    className="md:py-10"
    initial={{ y: 20, opacity: 0 }}
    animate={{ y: 0, opacity: 1 }}
    exit={{ y: -20, opacity: 0, display: 'none' }}
  >
    {children}
  </motion.div>
);
