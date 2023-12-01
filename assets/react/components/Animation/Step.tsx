import React from "react"
import { motion, AnimatePresence } from "framer-motion"

export const StepAnim = ({ isVisible, children } : {
    isVisible: boolean,
    children: React.ReactNode
}
) => (
    <AnimatePresence>
      {isVisible && ( 
        <motion.div
          initial={{ y: 20, opacity: 0 }}
          animate={{ y: 0, opacity: 1 }}
          exit={{ y: -20, opacity: 0, display: 'none' }}
        >
            {children}
        </motion.div>
       )} 
     
    </AnimatePresence>
  )