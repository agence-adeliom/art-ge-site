import React from 'react'
import InfoImage1 from '@images/informations-image.jpeg';
import InfoImage2 from '@images/informations-image-2.jpeg';

const AsideForm = ({step} : {
    step: number
}) => {
    return( 
        <>
            <div className="bg-neutral-600 max-lg:h-32 mobileLeftBleed lg:left-0 max-lg:order-first lg:col-start-9 lg:col-span-4 containerBleed relative">
            <img
                src={InfoImage1}
                alt="image de paysage"
                className={`${
                step === 1 ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
            <img
                src={InfoImage2}
                alt="image de paysage"
                className={`${
                step === 2 ? 'opacity-100' : 'opacity-0'
                } trans-default absolute object-cover w-full h-full`}
            ></img>
            </div>
        </>
    )
}

export default AsideForm