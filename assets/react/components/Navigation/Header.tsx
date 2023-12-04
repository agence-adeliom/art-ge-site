import React from 'react';
import Logo from '@images/logo/logo.svg';
import { Text } from '@components/Typography/Text';
import { Button } from '@components/Action/Button';
import { Link } from "react-router-dom";

const Header = ({step, ...props} : {
    step: number, 
    title ? : string | null
}) => {

    const totalSteps = 5;
    let actualStep = ((step) / totalSteps) * 100;
    if (actualStep > 100) {
        actualStep = 100;
    }
    return (
        <>
            <div className="container flex justify-between items-center py-8">
                <Link to="/">
                    <img src={Logo} alt="Logo ART GE"/>
                </Link>
                <Text color="neutral-500" className="hidden md:block" weight={400}>
                    { props.title === null ? props.title : ' Vos engagements pour un tourisme durable et responsable'}
                </Text>
                <Button variant="textOnly" icon={ 'fa-x' } weight={600}>Quitter</Button>
            </div>
            <div className="w-full h-1 bg-neutral-300 relative">
                <div className="h-full absolute left-0 top-0 bg-primary-600 rounded-top-right rounded-r-sm trans-default" style={{ 'width': `${actualStep}%` }}></div>
            </div>
        </>
        
    )
}

export default Header