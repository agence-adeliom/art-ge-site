import React, { useState, useContext, FunctionComponent } from 'react';
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
import { AnimatePresence } from 'framer-motion';
import { RoutePaths } from '@react/config/routes';
import useProgression from '@hooks/useProgression/useProgression';

const Informations: FunctionComponent = () => {
  const { step } = useProgression();

  return (
    <AnimatePresence>
      <div className="">
        <Header
          step={step}
          title={'Vos engagements pour un tourisme durable et responsable'}
        ></Header>
        <div className="container max-lg:pb-6 grid grid-cols-12 gap-6 md:h-[calc(100vh-108px)]">
          <div className="col-span-full lg:col-span-7 flex items-center overflow-auto relative">
            <div className="h-full w-full pl-1 flex">
              <div className="bg-white w-full">
                {step === 1 && <StepOne></StepOne>}
                {step === 2 && <StepTwo></StepTwo>}
                {step === 3 && <StepThree></StepThree>}
                {step === 4 && <StepFour></StepFour>}
              </div>
            </div>
          </div>

          <AsideForm step={step}></AsideForm>
        </div>

        <ConfirmationAnim isVisible={step === 5}>
          <Confirmation
            link={RoutePaths.FORM}
            title="Merci pour ces informations."
            subTitle="Parlons à présent de vos actions..."
          ></Confirmation>
        </ConfirmationAnim>
      </div>
    </AnimatePresence>
  );
};

export default Informations;
