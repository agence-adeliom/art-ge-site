import React, {useState} from 'react';
import Logo from '@images/logo/logo.svg';
import { Text } from '@components/Typography/Text';
import { Button } from '@components/Action/Button';
import { Link } from "react-router-dom";
import QuitFormModal from '@components/Modal/QuitFormModal';
const Header = ({step, ...props} : {
    step: number, 
    title ? : string | null,
    totalStep ? : number | null,
}) => {
    const totalSteps = props.totalStep ? props.totalStep : 5;
    let actualStep = ((step) / totalSteps) * 100;
    if (actualStep > 100) {
        actualStep = 100;
    }

    let hasTitle = props.title !== null ? true : false;

    const [openQuitPopin, setOpenQuitPopin] = useState(false);
    const closeModal = () => {
        setOpenQuitPopin(false)
    } 
    return (
        <>
            <div className="container relative flex justify-between items-center py-8">
                <Link to="/">
                    <img src={Logo} alt="Logo ART GE"/>
                </Link>
                { hasTitle ?
                    <Text color="neutral-500" className="hidden md:block" weight={400}>
                        { props.title }
                    </Text>
                    : false
                }
                
                <Button variant="textOnly" 
                    onClick={() =>setOpenQuitPopin(true)}
                    icon={ 'fa-x' } weight={600} className='max-md:justify-end !w-fit'>
                    Quitter
                </Button>

                {openQuitPopin && <QuitFormModal closeModal={closeModal}></QuitFormModal>}
                


            </div>
            <div className="w-full h-1 bg-neutral-300 relative">
                <div className="h-full absolute left-0 top-0 bg-primary-600 rounded-top-right rounded-r-sm trans-default" style={{ 'width': `${actualStep}%` }}></div>
            </div>
        </>
        
    )
}

export default Header