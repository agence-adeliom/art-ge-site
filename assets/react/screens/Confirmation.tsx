import React from "react";
import { Heading } from '@components/Typography/Heading';
import leafs from '@icones/leafs.svg';

const Confirmation = ({title, subTitle} : {
    title: string,
    subTitle: string
}) => {

    return (
        <div className="fixed bg-primary-600 z-50 flex items-center justify-center top-0 left-0 w-screen h-screen">
            <div className="text-center">
                <Heading variant="display-2" color="white">{title}</Heading>
                <Heading variant="display-4" className="mt-8" color="white">{subTitle}</Heading>
                <img src={leafs} className="block mx-auto mt-16"alt="image"></img>
            </div>
        </div>
    )
}

export default Confirmation