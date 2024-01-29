import React from "react";
import { Heading } from "@components/Typography/Heading";
import { Text } from "@components/Typography/Text";
import Topography from '@images/topography.svg';

const Header = () => {
    return (
        <div className="w-full">
            <div className="print:bg-white bg-primary-600 print:pb-0 py-12 px-10 w-full relative overflow-hidden">
                <Heading variant="display-4" weight={400} className="print:text-black text-white" >
                    ÉcoBoussole
                </Heading>
                <Text className="print:text-black text-white font-title" weight={400} size="2xl">
                    Autodiagnostic de votre engagement durable
                </Text>      
                <img src={Topography} alt="topography" className="right-0 absolute -top-[200%] opacity-20"/>
            </div>
        </div>
    )
}

export default Header