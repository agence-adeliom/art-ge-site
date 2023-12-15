import React from "react";
import Header from "@components/Navigation/Header";
import { Heading } from "@components/Typography/Heading";
import { Text } from "@components/Typography/Text";
import { Button } from "@components/Action/Button";
import ProgressBar  from "@components/ProgressBar/ProgressBar";
import ResultCard from "@components/Cards/ResultCard";
import { AnimatePresence } from "framer-motion"
import FooterResult from "@components/Navigation/FooterResults";
import Cta from "@components/Content/Cta";

const Resultats = () => {

    const resultPercentage = 80
    const submitDate = '22.11.2023'
    const url = window.location.href;
    return (
        <AnimatePresence>
            <>
                <Header button={{quitAction: false,name : 'Nous contacter', type :"primary", icon: 'fa-minus', iconSide: 'left', link: '/#'}} /> 
                <div className="bg-primary-600">
                    <div className="container grid grid-cols-12 gap-6 items-center pt-20 pb-8">
                        <div className="flex flex-col gap-4 col-span-full md:col-span-8 dark">
                            <Heading variant="display-2" color="white">
                                Félicitations !
                            </Heading>
                            <Heading variant="display-3" color="white">
                                Vos engagements font la différence.
                            </Heading>
                            <Text size="lg" color="white" className="mt-4">
                                Découvrez votre avancement thématique par thématique et accédez à des ressources pour faire évoluer les pratiques de votre établissement.
                            </Text>
                            <Button 
                                iconSide='left' 
                                size={'lg'} 
                                variant="primary" 
                                className="dark"
                                icon='fa-link'
                                onClick={() => navigator.clipboard.writeText(url)}
                            >Copier le lien</Button>
                        </div>
                        <div className="col-span-full md:col-span-4 bg-white p-10 h-fit">
                            <Heading variant="display-5">
                                Votre score
                            </Heading>
                            <Text className="font-title mb-4" size={"4xl"}>
                                <span className="text-6xl">{resultPercentage}</span> %
                            </Text>
                            <ProgressBar percentage={resultPercentage}></ProgressBar>
                            <Text className="mt-4" color="neutral-700" size={"sm"}>
                                {`Date de soumission : ${submitDate}`}
                            </Text>
                            
                        </div>
                    </div>
                </div>

                <div className="bg-primary-50 relative">
                    <div className="absolute top-0 left-0 w-full h-20 bg-primary-600"></div>
                    <div className="container relative z-10 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <ResultCard percentage={resultPercentage} src={'https://images.unsplash.com/photo-1610093674388-cee0337f2684?q=80&w=2940&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'} title="Biodiversité et conservation de la nature du site"></ResultCard>
                        <ResultCard percentage={20} title="Biodiversité"></ResultCard>
                        <ResultCard percentage={33} title="Conservation de la nature du site"></ResultCard>
                        <ResultCard percentage={resultPercentage} title="Nature du site"></ResultCard>
                    </div>

                </div>
                <div className="bg-primary-50 py-20">
                    <div className="container">
                        <Cta></Cta>
                    </div>
                </div>
                
                <FooterResult></FooterResult>
            </>
            
        </AnimatePresence>
        
    )
}

export default Resultats