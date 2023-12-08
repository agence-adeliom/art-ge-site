import React, { useEffect, useState, useRef, useContext } from 'react';
import Header from '@components/Navigation/Header';
import StepOne from '@screens/StepOne';
import StepTwo from '@screens/StepTwo';
import StepThree from '@screens/StepThree';
import StepFour from '@screens/StepFour';
import Confirmation from '@screens/Confirmation';
import { StepAnim } from '@components/Animation/Step';
import { ConfirmationAnim } from '@components/Animation/Confirmation';
import { QuestionsContext, UserContext } from '@components/Context/Context';
import AsideForm from '@components/Content/AsideForm';

import { object, string, number, InferType, setLocale } from 'yup';

import * as yup from 'yup';

const Informations = () => {
  const userContext = useContext(UserContext);
  const data: Object = userContext.data;

  const establishmentInfo = {
    establishmentName: '',
    address: '',
    zipCode: '',
    city: '',
  };

  const [errorMessage, seterrorMessage] = useState('');

  // Step 1 : User information
  let userData = userContext.userData;
  let setUserData: any = userContext.setUserData;

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
  const { firstname, lastname, email, tel } = userContext.userData;

  // Establishment information destructuring
  const { establishmentName, address, zipCode, city } = establishmentData;

  // Set Input value in the userState
  const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    setUserData({ ...userData, [event.target.id]: event.target.value });
  };
  // Set Input value in the establishmentState
//   const handleChangeEstablishment = (
//     event: React.ChangeEvent<HTMLInputElement>,
//   ) => {
//     setEstablishmentData({
//       ...establishmentData,
//       [event.target.id]: event.target.value,
//     });
//     zipCodeAutocomplete(event);
//   };

  
  const nextStep = () => {
    setStep(step + 1);
  };


  const [isLoading, setIsLoading] = useState(true);

  console.log(isLoading)

  
  
  const inputClass: string =
    'border-0 border-b border-neutral-500 block w-full mt-4 pb-2 focus:ring-0 focus:border-secondary-200 trans-default';
  return (
    <div className="">
      <Header
        step={step}
        title={'Vos engagements pour un tourisme durable et responsable'}
      ></Header>
      <div className="container max-lg:pb-6 grid grid-cols-12 gap-6 md:h-[calc(100vh-108px)]">
        <div className="col-span-full lg:col-span-7 flex items-center overflow-auto relative">
          <div className="h-full w-full pl-1 flex">
            <div className="bg-white w-full">
              <StepAnim isVisible={step === 3 ? true : false}>
                <StepOne
                    nextStep={nextStep}
                ></StepOne>
                
              </StepAnim>
              <StepAnim isVisible={step === 5 ? true : false}>
                <StepTwo
                  setEtablissement={setEtablissement}
                  etablissement={etablissement}
                  nextStep={nextStep}
                ></StepTwo>
              </StepAnim>
              <StepAnim isVisible={step === 1 ? true : false}>
                <StepThree
                  isRestaurant={isRestaurant}
                  setIsRestaurant={setIsRestaurant}
                  isGreenSpace={isGreenSpace}
                  setIsGreenSpace={setIsGreenSpace}
                  nextStep={nextStep}
                  setIsLoading={setIsLoading}
                ></StepThree>
              </StepAnim>
              <StepAnim isVisible={step === 4 ? true : false}>
                <StepFour
                nextStep={nextStep}
                establishmentData={establishmentData} 
                setEstablishmentData={setEstablishmentData}
                />
              </StepAnim>

            </div>
          </div>
        </div>
        
        <AsideForm step={step}></AsideForm>
      </div>

      <ConfirmationAnim isVisible={step === 2 && isLoading === false ? true : false}>
          <Confirmation
            link="/form"
            title="Merci pour ces informations."
            subTitle="Parlons à présent de vos actions..."
          ></Confirmation>
      </ConfirmationAnim>
    </div>
  );
};

export default Informations;
