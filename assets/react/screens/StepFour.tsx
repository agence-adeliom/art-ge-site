import React, { useState } from 'react';
import { Heading } from '@components/Typography/Heading';
import { Text } from '@components/Typography/Text';
import { Button } from '@components/Action/Button';
import { TextInput } from '@components/Fields/TextInput';
import { Fields } from '@react/types/Fields';

const StepFour = ({
  handleChange,
  address,
  establishmentName,
  city,
  zipCode,
  nextStep,
  zipResult,
  setEstablishmentData,
  establishmentData,
  openDropdown,
  setOpenDropdown,
}: {
  handleChange: Function;
  address: string;
  establishmentName: string;
  city: string;
  zipCode: string;
  nextStep: Function;
  zipResult: Array<any>;
  setEstablishmentData: Function;
  establishmentData: object;
  openDropdown: boolean;
  setOpenDropdown: Function;
}) => {
  return (
    <>
      {/*   <Heading variant="display-4">L’adresse de votre établissement...</Heading>
      <Text className="mt-6" color="neutral-500" weight={400} size="sm">
        Ces coordonnées nous permettent de vous situer dans le Grand Est.
      </Text>
      <div className="flex-wrap md:flex-nowrap flex gap-6 w-full mt-8">
        <TextInput
          containerClass="w-full md:w-1/2"
          label={'Établissement'}
          name="email"
          type={Fields.TEXT}
          value={establishmentName}
          placeholder={'Nom de l’établissement'}
          control={control}
        ></TextInput>
        <TextInput
          containerClass="w-full md:w-1/2"
          label={{ className: 'block', name: 'Adresse' }}
          id="address"
          input={{
            type: 'text',
            className: 'block',
            value: address,
            handleChange: handleChange,
            placeHolder: "Ex : 8 rue de l'école",
          }}
        ></TextInput>
      </div>
      <div className="flex-wrap md:flex-nowrap flex gap-6 w-full mt-8">
        <div className="relative w-full md:w-1/2">
          <TextInput
            containerClass="w-full"
            label={{ className: 'block', name: 'Code postal' }}
            id="zipCode"
            input={{
              type: 'text',
              className: 'block',
              value: zipCode,
              handleChange: handleChange,
              placeHolder: 'Ex : 67000',
            }}
          ></TextInput>
          <div
            className={`${
              zipResult.length === 0 || openDropdown === false
                ? 'hidden'
                : 'block'
            } bg-white w-full z-50 h-[200px] overflow-auto mt-6  absolute shadow-[0_0_8px_2px_rgba(0,0,0,.05)]`}
          >
            {zipResult !== null &&
              zipResult.map((item: any, index: number) => (
                <div
                  className="result-zip"
                  key={index}
                  onClick={() => {
                    setEstablishmentData({
                      ...establishmentData,
                      zipCode: item.zip,
                      city: item.name,
                    });
                    setOpenDropdown(false);
                  }}
                >
                  {item.zip} {item.name}
                </div>
              ))}
          </div>
        </div>

        <TextInput
          containerClass="w-full md:w-1/2"
          label={{ className: 'block', name: 'Ville' }}
          id="city"
          input={{
            type: 'text',
            className: 'block',
            value: city,
            handleChange: handleChange,
            placeHolder: 'Ex : Strasbourg',
          }}
        ></TextInput>
      </div>

      <Button
        size="lg"
        className="mt-8"
        icon="fa-minus"
        iconSide="left"
        disabled={
          establishmentName === '' ||
          address === '' ||
          zipCode === '' ||
          city === ''
            ? true
            : false
        }
        onClick={event => {
          event.preventDefault();
          nextStep();
        }}
      >
        Suivant
      </Button> */}
    </>
  );
};

export default StepFour;
