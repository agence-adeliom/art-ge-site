import React, {useState} from 'react';
import Logo from '@images/logo/logo.svg';
import { Text } from '@components/Typography/Text';
import { Button } from '@components/Action/Button';
import { Link } from "react-router-dom";
import QuitFormModal from '@components/Modal/QuitFormModal';

const Header = ({step, ...props} : {
    step?: number, 
    title ? : string | null,
    totalStep ? : number | null,
    button ? : {
        name?: string ,
        type? :  'primary' | 'secondary' | 'tertiary' | 'textOnly' | undefined, 
        icon: string,
        iconSide?: 'left' | 'right',
        link? : string,
        quitAction? : boolean
    }
}) => {
    const totalSteps = props.totalStep ? props.totalStep : 5;
    let actualStep = ((step!) / totalSteps) * 100;
    if (actualStep > 100) {
        actualStep = 100;
    }

    let hasTitle = props.title !== null ? true : false;

    const [openQuitPopin, setOpenQuitPopin] = useState(false);
    const closeModal = () => {
        setOpenQuitPopin(false)
    } 
    const button = props.button ? props.button : null
    const variantBtn = button?.type != undefined ? button!.type : 'textOnly'
    const nameBtn = button?.name ? button!.name : 'Quitter'
    const iconBtn = button?.icon ? button!.icon : 'fa-x'
    const iconSideBtn = button?.iconSide ? button!.iconSide : 'right'
    const linkBtn = button?.link ? button!.link : null
    const actionBtn = button?.quitAction 
    return (
        <>
            <div className="container relative flex justify-between items-center py-8">
                <a href="https://www.art-grandest.fr/" target='_blank' >
                    <img src={Logo} alt="Logo ART GE"/>
                </a>
                { hasTitle ?
                    <Text color="neutral-500" className="hidden md:block" weight={400}>
                        { props.title }
                    </Text>
                    : false
                }
                

                { actionBtn !== false ?
                    <>
                        <Button 
                            variant={variantBtn}
                            iconSide={iconSideBtn}
                            onClick={() =>setOpenQuitPopin(true)}
                            icon={ iconBtn } weight={600} className='max-md:justify-end !w-fit'>
                            { nameBtn }
                        </Button>
                        {openQuitPopin && <QuitFormModal closeModal={closeModal}></QuitFormModal>}
                    </>
                    :
                    <>
                        <Button 
                            variant={variantBtn}
                            iconSide={iconSideBtn}
                            {...linkBtn && {href: linkBtn}}
                            icon={ iconBtn } weight={600} className='max-md:justify-end !w-fit'>
                            { nameBtn }
                        </Button>
                    </>
                        
                
                }
                
                


            </div>
            { step != null  && 
            <div className="w-full h-1 bg-neutral-300 relative">
                <div className="h-full absolute left-0 top-0 bg-primary-600 rounded-top-right rounded-r-sm trans-default" style={{ 'width': `${actualStep}%` }}></div>
            </div>}
        </>
        
    )
}

export default Header