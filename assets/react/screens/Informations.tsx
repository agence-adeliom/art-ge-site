import React, {useState} from 'react';
import Header from '@components/Navigation/Header';
import InfoImage1 from '@images/informations-image.jpeg';
import InfoImage2 from '@images/informations-image-2.jpeg';
import StepOne from '@screens/StepOne';
import StepTwo from '@screens/StepTwo';
import StepThree from '@screens/StepThree';
import StepFour from '@screens/StepFour';
import {Icon} from '@components/Typography/Icon'
import Confirmation from '@screens/Confirmation';



import { object, string, number, InferType,  setLocale } from 'yup';

import * as yup from 'yup';

const Informations = () => {
    
    const data = {
        firstname: '',
        lastname: '',
        email: '',
        tel: ''
    }

    const establishmentInfo = {
        establishmentName: '',
        address: '',
        zipCode: '',
        city: ''
    }

    
    const [errorMessage, seterrorMessage] = useState('');

    // Step 1 : User information 
    const [userData, setUserData] = useState(data);
    const [legalChecked, setLegalChecked] = useState(false);
    // Step 2 : Type d'établissement 
    const [etablissement, setEtablissement] = useState('');
    // Step 3 : Offre de restaurant ? 
    const [isRestaurant, setIsRestaurant] = useState('');
    // Step 3 : Espace vert ? 
    const [isGreenSpace, setIsGreenSpace] = useState('');
    // Step 4 : Establishment information 
    const [establishmentData, setEstablishmentData] = useState(establishmentInfo);
   
    // Current step + setStep
    const [step, setStep] = useState(1);

    // User information destructuring
    const {firstname, lastname, email, tel} = userData;

    // Establishment information destructuring
    const {establishmentName, address, zipCode, city} = establishmentData;

    // Validation rules
    setLocale({
        mixed: {
          default: 'Não é válido',
        },
        string: {
            matches: 'Le numéro de téléphone ne doit pas contenir de lettres',
            min: 'Le numéro doit contenir ${min} chiffres',
            max: 'Le numéro doit contenir ${min} chiffres',
            email: 'Adresse email invalide',
        },
      });
    // Step 1 User schema
    let userSchema =  yup.object().shape({
        firstname: yup.string(),
        lastname: yup.string(),
        email: yup.string().email(),
        tel: yup.string().matches(/[0-9]/).min(10).max(10),
    })

    // Step 2 Establishment schema
    let establishmentSchema =  yup.object().shape({
        establishmentName: yup.string(),
        address: yup.string(),
        zipCode: yup.string().matches(/[0-9]/).min(5).max(5),
        city: yup.string(),
    })
    
    // Set Input value in the userState
    const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setUserData({...userData, [event.target.id]: event.target.value })
    }
    // Set Input value in the establishmentState
    const handleChangeEstablishment = (event: React.ChangeEvent<HTMLInputElement>) => {
        setEstablishmentData({...establishmentData, [event.target.id]: event.target.value })
    }

   const acceptLegal = (event : React.ChangeEvent<HTMLInputElement>) => {
       setLegalChecked(event.target.checked)
       console.log(event.target.checked)
   }

   const nextStep = () => {
       setStep(step + 1)
   }

   // First step validation
   const handleSubmit = async (event: React.FormEvent) => {
       event.preventDefault();
       
       try {
        await userSchema.validate( userData );
        console.log('true');
        nextStep();
        console.log(step)
      } catch (error: any) {
        console.log('false' + error)
      }
     
    }

    const inputClass : string = 'border-0 border-b border-neutral-500 block w-full mt-4 pb-2 focus:ring-0 focus:border-secondary-200 trans-default'
    return (
        <div className="">
            <Header step={step}></Header>
            <div className="container max-lg:pb-6 grid grid-cols-12 gap-6 md:h-[calc(100vh-108px)]">
                <div className="col-span-full lg:col-span-7 flex items-center md:py-10 overflow-auto relative">
                    <form className="h-full w-full pl-1 flex items-center">

                        <div className="bg-white w-full">

                            { 
                                step === 1 ? (
                                    <StepOne 
                                    handleChange={handleChange} 
                                    handleSubmit={handleSubmit} 
                                    acceptLegal={acceptLegal} 
                                    firstname={firstname} 
                                    lastname={lastname} 
                                    email={email} tel={tel}
                                    inputClass={inputClass}
                                    legalChecked={legalChecked}
                                    ></StepOne>
                                ) 
                                : step === 2 ? (
                                    <StepTwo
                                    setEtablissement={setEtablissement}
                                    etablissement={etablissement}
                                    nextStep={nextStep}
                                    ></StepTwo>
                                ) 
                                : step === 3 ? (
                                    <StepThree
                                        isRestaurant={isRestaurant}
                                        setIsRestaurant={setIsRestaurant}
                                        isGreenSpace={isGreenSpace}
                                        setIsGreenSpace={setIsGreenSpace}
                                        nextStep={nextStep}
                                    ></StepThree>
                                )
                                : step === 4 ? (
                                    <StepFour 
                                        establishmentName={establishmentName} 
                                        handleChange={handleChangeEstablishment}
                                        address={address}
                                        zipCode={zipCode}
                                        city={city}
                                        nextStep={nextStep}
                                    />
                                ) : step === 5 ? (
                                    <Confirmation
                                        title="Merci pour ces informations."
                                        subTitle="Parlons à présent de vos actions..."
                                    ></Confirmation>
                                ) : (
                                    <p>fallback</p>
                                )

                                
                            }
                            
                            
                        </div>

                        

                        
                            
                        
                    </form>
                </div>
                <div className="bg-neutral-600 max-lg:h-32 mobileLeftBleed lg:left-0 max-lg:order-first lg:col-start-9 lg:col-span-4 containerBleed relative">
                    <img src={InfoImage1} alt="image de paysage" className={`${step === 1 ? 'opacity-100' : 'opacity-0'} trans-default absolute object-cover w-full h-full`}></img>
                    <img src={InfoImage2} alt="image de paysage" className={`${step === 2 ? 'opacity-100' : 'opacity-0'} trans-default absolute object-cover w-full h-full`}></img>
                </div>
            </div>
        </div>
    )
}

export default Informations