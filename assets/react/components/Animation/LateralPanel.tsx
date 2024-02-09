import React from "react"
import { motion } from "framer-motion"

export const LateralPanelAnim = ({ children } : {
    children: React.ReactNode,

}

) => (
    <>
        <>
            <motion.div 
            key={`lateralPanel`} 
            className="w-screen md:w-[560px] h-screen overflow-auto fixed top-0 z-[100] right-0"
            initial={{  x: '100%' }}
            animate={{  x: 0 }}
            exit={{  opacity: 0, x: '100%' }}
            transition={{
                ease: "easeIn",
                duration: 0.3
              }}
            >
                {children}
            </motion.div>
        </>     
    </>
  )