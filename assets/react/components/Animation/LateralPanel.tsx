import React, { ReactElement } from "react"
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
            key="lateralPanel"   
            className="w-screen md:w-[560px] h-screen overflow-auto fixed top-0 z-[100] right-0"
            initial={{  opacity: 0 }}
            animate={{  opacity: 1 }}
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