import React, { useId } from "react"
import { motion } from "framer-motion"

export const LateralPanelAnim = ({ isVisible, children } : {
    isVisible: boolean,
    children: React.ReactNode,

}

) => (
    <>
      {isVisible && ( 
        <>
            <motion.div 
            key={`lateralPanel-${useId()}`} 
            className="w-screen md:w-[560px] h-screen overflow-auto fixed top-0 z-[100] right-0"
            initial={{  x: 500 }}
            animate={{  x: 0 }}
            exit={{  opacity: 0 }}
            transition={{
                ease: "easeIn",
                duration: 0.3
              }}
            >
                {children}
            </motion.div>
        </>
        
        
       )} 
     
    </>
  )