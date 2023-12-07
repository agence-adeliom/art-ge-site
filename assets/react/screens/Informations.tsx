import React, {useEffect, useState, useRef, useContext} from 'react';
import Header from '@components/Navigation/Header';
import InfoImage1 from '@images/informations-image.jpeg';
import InfoImage2 from '@images/informations-image-2.jpeg';
import StepOne from '@screens/StepOne';
import StepTwo from '@screens/StepTwo';
import StepThree from '@screens/StepThree';
import StepFour from '@screens/StepFour';
import {Icon} from '@components/Typography/Icon'
import Confirmation from '@screens/Confirmation';
import { motion, AnimatePresence } from "framer-motion"
import {useNavigate} from 'react-router-dom'
import {StepAnim} from '@components/Animation/Step';
import {QuestionsContext, UserContext} from '@components/Context/Context';


import { object, string, number, InferType,  setLocale } from 'yup';

import * as yup from 'yup';

const Informations = () => {


    const userContext = useContext(UserContext)
    const data : Object = userContext.data


    const establishmentInfo = {
        establishmentName: '',
        address: '',
        zipCode: '',
        city: ''
    } 

    
    const [errorMessage, seterrorMessage] = useState('');

    // Step 1 : User information 
    let userData = userContext.userData
    let setUserData : any = userContext.setUserData
    

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
    const {firstname, lastname, email, tel} = userContext.userData;
 
    // Establishment information destructuring
    const {establishmentName, address, zipCode, city} = establishmentData;

    // Validation rules
    setLocale({
        mixed: {
          default: 'Non valide',
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
        zipCodeAutocomplete(event)
    }

    const [zipResult, setZipResult] = useState([]);

    const [openDropdown, setOpenDropdown] = useState(false)

    // Function d'autocompletion du zip
    const zipCodeAutocomplete = (event: React.ChangeEvent<HTMLInputElement>) => {
        let inputId = event.target.id;
        let autoCompleteAPI = 'https://art-grand-est.ddev.site/api/insee/'
        if (inputId === 'zipCode') {
            setOpenDropdown(true)
            let resultValue = event.target.value
            let apiResult = autoCompleteAPI + resultValue
            fetch(apiResult)
            .then(async (response: Response) => {
                setZipResult(await response.json());
            })
            .catch(() => {
                console.log('error')
            });
        } 
    }
    

   const acceptLegal = (event : React.ChangeEvent<HTMLInputElement>) => {
       setLegalChecked(event.target.checked)
   }

   const nextStep = () => {
       setStep(step + 1)
   }

   // First step validation
   const handleSubmit = async (event: React.FormEvent) => {
       event.preventDefault();
       
       try {
        await userSchema.validate( userData );
        nextStep();
      } catch (error: any) {
        console.log('false' + error)
      }
     
    }

    const questionContext = useContext(QuestionsContext)
    let setQuestions : any = questionContext.setQuestions
    let questions : any = questionContext.questions
    let initialQuestions : any = questionContext.initialQuestions

    const prevCountRef = useRef(initialQuestions);

    const handleSubmitToForm = () => {

        let formAPI = 'api/form?green_space='
        let greenSpaceChoice = isGreenSpace === 'true' ? true : isGreenSpace === 'false' ? false : null
        let formAPIresults = formAPI + greenSpaceChoice
               
        if (greenSpaceChoice !== null) {
            fetch(formAPIresults)
            .then(async (response: Response) => {
                setQuestions(await response.json())
                window.localStorage.setItem('allQuestions', JSON.stringify(await response.json()));
            })
            .catch(() => {
                console.log('error')
            });
        }
        
     }

     console.log(isGreenSpace)

    //  useEffect(() => {
    //     window.localStorage.setItem('allQuestions', JSON.stringify(questions));
    //     }, [questions]);


     const redirectToForm = useNavigate()

     useEffect(() => {
        if (questions != prevCountRef.current) {
            setTimeout(() => {
                redirectToForm('/form', {replace: true})
             }, 2000)
        }
     }, [questions])


    const inputClass : string = 'border-0 border-b border-neutral-500 block w-full mt-4 pb-2 focus:ring-0 focus:border-secondary-200 trans-default'
    return (
        <div className="">
            <Header step={step} title={'Vos engagements pour un tourisme durable et responsable'}></Header>
            <div className="container max-lg:pb-6 grid grid-cols-12 gap-6 md:h-[calc(100vh-108px)]">
                <div className="col-span-full lg:col-span-7 flex items-center md:py-10 overflow-auto relative">
                    <form className="h-full w-full pl-1 flex">

                        <div className="bg-white w-full">

                             <StepAnim isVisible={step === 1 ? true : false}>
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
                            </StepAnim> 
                            <StepAnim isVisible={step === 2 ? true : false}>
                                <StepTwo
                                    setEtablissement={setEtablissement}
                                    etablissement={etablissement}
                                    nextStep={nextStep}
                                ></StepTwo>
                            </StepAnim>  
                            <StepAnim isVisible={step === 3 ? true : false}>
                                <StepThree
                                    isRestaurant={isRestaurant}
                                    setIsRestaurant={setIsRestaurant}
                                    isGreenSpace={isGreenSpace}
                                    setIsGreenSpace={setIsGreenSpace}
                                    nextStep={nextStep}
                                ></StepThree>
                            </StepAnim> 
                            <StepAnim isVisible={step === 4 ? true : false}>
                                <StepFour 
                                    establishmentName={establishmentName} 
                                    handleChange={handleChangeEstablishment}
                                    address={address}
                                    zipCode={zipCode}
                                    city={city}
                                    nextStep={nextStep}
                                    zipResult={zipResult}
                                    setEstablishmentData={setEstablishmentData}
                                    establishmentData={establishmentData}
                                    openDropdown={openDropdown}
                                    setOpenDropdown={setOpenDropdown}
                                    handleSubmitToForm={handleSubmitToForm}
                                />
                            </StepAnim> 

                            {step === 5 && 
                                <Confirmation
                                    title="Merci pour ces informations."
                                    subTitle="Parlons à présent de vos actions..."
                                ></Confirmation>
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