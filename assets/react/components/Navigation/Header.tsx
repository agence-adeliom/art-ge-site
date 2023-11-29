import React from 'react';
import Logo from '@images/logo/logo.svg';
import { Text } from '@components/Typography/Text';
import { Button } from '@components/Action/Button';

const Header = () => {
    return (
        <>
            <div className="container flex justify-between items-center py-8">
                <img src={Logo} alt="Logo ART GE"/>
                <Text color="neutral-500" weight={400}>
                    Vos engagements pour un tourisme durable et responsable
                </Text>
                <Button variant="textOnly" weight={600}>Quitter</Button>
            </div>
            <div className="w-full h-1 bg-neutral-300 relative">
                <div className="h-full absolute left-0 top-0 bg-primary-600 rounded-top-right rounded-r-sm" style={{ 'width': '30%' }}></div>
            </div>
        </>
        
    )
}

export default Header