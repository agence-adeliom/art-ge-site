import React from 'react';
import { Link } from "react-router-dom";
import Logo from '@images/logo/logo.svg';
import lightBulbOn from '@icones/lightbulb-on.svg';
import peopleGroup from '@icones/people-group.svg';
import lapTop from '@icones/laptop.svg';
import bioDiversity from '@icones/bio-diversite.svg';
import leafs from '@icones/leafs.svg';
import { Heading } from '@components/Typography/Heading';
import { Text } from '@components/Typography/Text';
import { Button } from '@components/Action/Button/Button';
import Footer from '@components/Navigation/Footer';
import Aside from '@components/Content/Aside';

function Home() {

    const navigateToFirstStep = (event: Event | undefined) => {
        event?.preventDefault();
    }

    return (
        <div className="w-screen overflow-hidden">
        <div className="h-screen overflow-auto">
            <div className="container">
                <div className="h-screen w-full grid grid-cols-12 auto-rows-min">
                    <div className="col-span-full max-lg:mb-10 lg:col-span-7 mt-20">
                        <img className="w-[282px] h-[93px]" src={Logo} alt=""></img>
                        <div className="flex flex-col gap-4">
                            <Heading variant="display-2" className="mt-12">Bienvenue sur notre calculateur tourisme durable</Heading>
                            <Text color="neutral-700">L’Agence Régionale du Tourisme Grand Est vous invite à compléter ce questionnaire pour évaluer votre niveau d’engagement durable, connaître vos points forts ainsi que vos axes d’amélioration.</Text>
                        </div>
                            
                        <div className="border p-4 border-secondary-600 mt-10">
                            <div className="flex items-center gap-2 mb-2">
                                <img src={lightBulbOn} alt="icon ampoule" />
                                <Text weight={600}>Avant de vous lancer</Text>
                            </div>
                            <ul className="list-disc list-inside marker:text-secondary-800">
                                <li className="font-normal">Un <strong>diagnostic de votre engagement dans la transition</strong></li>
                                <li className="font-normal"><strong>Gratuit</strong>, facile à prendre en main, <strong>sans engagement</strong> avec un résultat <strong>immédiat</strong>.</li>
                                <li className="font-normal"><strong>Accès à des ressources</strong> pour continuellement optimiser vos pratiques.</li>
                            </ul>
                        </div>
                        <Link to="/informations" replace={true}>
                            <Button 
                            size="lg" 
                            className="mt-8"
                            icon="fa-minus"
                            iconSide="left"
                            onClick={ () => navigateToFirstStep(event) }>
                                Commencer
                            </Button>
                        </Link>
                        
                        <div className="mt-8">
                            <Text weight={600}>Des questions ?</Text>
                            <Text>Retrouvez plus d’informations sur ce questionnaire dans notre <a href="#" className="classic-link">FAQ</a></Text>
                        </div>
                    </div>
                    
                    <Aside></Aside>
                   
                   <Footer></Footer>
                    
                </div>
            </div>
        </div>
    </div>
    )
}

export default Home;