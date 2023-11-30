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
                    {/* Aside */}
                    <div className="col-span-full lg:col-start-9 lg:row-span-2 lg:col-span-4 dark  h-full lg:min-h-screen py-10 lg:p-10 pr-0 flex items-center relative">
                        <div className="mobileLeftBleed containerBleed h-full absolute top-0 lg:left-0 z-0 bg-secondary-950">
                            <img src={leafs} className="block absolute bottom-0 right-0 w-72 aspect-square" alt="background feuilles" />
                        </div>
                        <div className="flex flex-col gap-10 relative z-2">
                            {/* First Element */}
                            <div className="flex gap-4">
                                <div className="iconClass bg-secondary-200">
                                    <img src={peopleGroup} alt="icon groupe"/>
                                </div>
                                <div className="flex flex-col gap-2">
                                    <Heading weight={400} variant="display-4" color="white">Avant de vous lancer :</Heading>
                                    <Text size="sm" weight={500} color="white">Prévoyez environ 15 minutes pour remplir le questionnaire.</Text>
                                </div>
                            </div>
                            {/* Second Element */}
                            <div className="flex gap-4">
                                <div className="iconClass bg-secondary-200">
                                    <img src={lapTop} alt="icon ordinateur"/>
                                </div>
                                <div className="flex flex-col gap-2">
                                    <Heading weight={400} variant="display-4" color="white">Diagnostic :</Heading>
                                    <Text size="sm" weight={500} color="white">Vous visualiserez votre diagnostic instantanément, vos actions à capitaliser ainsi que les nouvelles orientations à donner à votre stratégie.</Text>
                                </div>
                            </div>
                            {/* Third Element */}
                            <div className="flex gap-4">
                                <div className="iconClass bg-secondary-200">
                                    <img src={bioDiversity} alt="icon bio diversité"/>
                                </div>
                                <div className="flex flex-col gap-2">
                                    <Heading weight={400} variant="display-4" color="white">Et ensuite ?</Heading>
                                    <Text size="sm" weight={500} color="white">Profitez de nos outils ainsi que de nos accompagnements personnalisés.</Text>
                                </div>
                            </div>
                        </div>

                        
                    </div>
                    {/* Footer */}
                    <div className="col-span-full lg:col-span-8 lg:row-start-2 text-white bg-neutral-700 h-fit py-10 lg:py-6 self-end relative lg:mt-4">
                        <div className="w-screen mobileLeftBleed lg:w-full absolute h-full lg:-left-1/2 bg-neutral-700 top-0 z-0"></div>
                        <div className="relative z-2">
                            <nav>
                                <ul className="flex items-center max-md:justify-center gap-6 text-sm font-normal flex-wrap">
                                    <li><a href="">Mentions légales</a></li>
                                    <li><a href="">Gestion des cookies</a></li>
                                    <li><a href="">Politique de confidentialité</a></li>
                                </ul>
                            </nav>
                        </div>
                        <div className="flex-col md:flex-row flex items-center gap-6 justify-between mt-6 relative z-2">
                            <Text color="white" weight={400} size="sm">2023 ©ARTGE - Tous droits réservés</Text>
                            <div>
                                <a href="https://adeliom.com/" className="flex items-center gap-2 mr-6" target="_blank">
                                    <Text weight={400} size="sm" color="white">Conception</Text>
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11.9991 5.998C11.9991 2.69331 9.30576 0 5.99707 0V6.002H11.9951L11.9991 5.998Z" fill="#6E6E6E"/>
                                        <path d="M3.001 6.002C4.6584 6.002 6.002 4.6584 6.002 3.001C6.002 1.34359 4.6584 0 3.001 0C1.34359 0 0 1.34359 0 3.001C0 4.6584 1.34359 6.002 3.001 6.002Z" fill="#CFCFCF"/>
                                        <path d="M12.0002 5.99817H3.00122C1.35087 6.00616 0.012207 7.34482 0.012207 8.99917C0.012207 10.6535 1.35486 12.0002 3.01321 12.0002H12.0002V5.99817Z" fill="#6F6F6F"/>
                                        <path d="M5.99707 5.99789C5.99707 9.30658 8.68638 11.9959 11.9951 11.9959V5.9939H5.99707V5.99789Z" fill="#373737"/>
                                    </svg>
                                    <Text weight={400} size="sm" color="white">Agence Adeliom</Text>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    )
}

export default Home;