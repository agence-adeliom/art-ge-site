import React, { InputHTMLAttributes, useState } from 'react';
import { Heading } from '@components/Typography/Heading';
import { Text } from '@components/Typography/Text';
import { Button } from '@components/Action/Button';
import { TextInput } from '@components/Fields/TextInput';

import { ObjectSchema } from 'yup';
import * as yup from 'yup';
import { yupResolver } from '@hookform/resolvers/yup';
import { SubmitHandler, useForm } from 'react-hook-form';
import { useValidation } from '@hooks/useValidation';
import { Fields } from '@react/types/Fields';


interface DataFields {
  establishment: string;
  address: string;
  zip: string;
  city: string;
}

const StepFour = ({ nextStep, establishmentData, setEstablishmentData}: 
  {
    nextStep: Function;
    establishmentData: any,
    setEstablishmentData: any
  }) => {


  // Function d'autocompletion du zip

  const [zipResult, setZipResult] = useState([]);

  const [openDropdown, setOpenDropdown] = useState(false);

  const zipCodeAutocomplete = (event: React.ChangeEvent<HTMLInputElement>) => {
    let inputId = event.target.id;
    let autoCompleteAPI = 'https://art-grand-est.ddev.site/api/insee/';
    if (inputId === 'zip') {
      setOpenDropdown(true);
      let resultValue = event.target.value;
      let apiResult = autoCompleteAPI + resultValue;
      fetch(apiResult)
        .then(async (response: Response) => {
          setZipResult(await response.json());
        })
        .catch(() => {
          console.log('error');
        });
    }
  };

  const { textRequired, zipCodeRequired } = useValidation();

  const schema: ObjectSchema<DataFields> = yup.object().shape({
    establishment: textRequired,
    address: textRequired,
    zip: zipCodeRequired,
    city: textRequired,
  });

  const {
    handleSubmit,
    control,
    formState: { isValid },
  } = useForm<DataFields>({
    mode: 'onBlur',
    resolver: yupResolver(schema),
  });

  const onSubmit: SubmitHandler<DataFields> = data => {
    console.log('data', data);
  };


  return (
    <>
      <form onSubmit={handleSubmit(onSubmit)}> 
        <Heading variant="display-4">L’adresse de votre établissement...</Heading>
        <Text className="mt-6" color="neutral-500" weight={400} size="sm">
          Ces coordonnées nous permettent de vous situer dans le Grand Est.
        </Text>
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6 w-full mt-8">
        
        
          <TextInput
            containerClass="col-span-1"
            label={'Établissement'}
            name={'establishment'}
            type={Fields.TEXT}
            placeholder={'Nom de l’établissement'}
            control={control}
          ></TextInput>

          <TextInput
            containerClass="col-span-1"
            label={'Adresse'}
            name={'address'}
            type={Fields.TEXT}
            placeholder={`Ex : 8 rue de l'école`}
            control={control}
          ></TextInput>
          <div className="relative col-span-1">

            <TextInput
              label={'Code postal'}
              name={'zip'}
              type={Fields.TEXT}
              placeholder={'Ex : 67000'}
              handleChange={zipCodeAutocomplete}
              control={control}
            ></TextInput>
            <div
              className={`${
                zipResult.length === 0 || openDropdown === false
                  ? 'hidden'
                  : 'block'
              } bg-white w-full z-50 max-h-[200px] overflow-auto mt-6 absolute shadow-[0_0_8px_2px_rgba(0,0,0,.10)]`}
            >
              {zipResult !== null &&
                zipResult.map((item: any, index: number) => (
                  <div
                    className="result-zip"
                    key={index}
                    onClick={() => {
                      // let zip : HTMLInputElement = document.querySelector('#zip');
                      // let city : HTMLInputElement = document.querySelector('#city');
                      // zip.value = item.zip;
                      // city.value = item.name;
                      setOpenDropdown(false);
                    }}
                  >
                    {item.zip} {item.name}
                  </div>
                ))}
            </div>
          </div>
          



          <TextInput
            containerClass="col-span-1"
            label={'Ville'}
            name={'city'}
            type={Fields.TEXT}
            placeholder={'Ex : Strasbourg'}
            control={control}
          ></TextInput>
        
        
          {/* <TextInput
        
        
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
            ></TextInput> */}
            {/* <div
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
            </div> */}
          </div>

          {/* <TextInput
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
          ></TextInput> */}
        {/* </div> */}

        <Button
          size="lg"
          className="mt-8"
          icon="fa-minus"
          iconSide="left"
          disabled={!isValid}
          //onClick={(event) => {event.preventDefault(); nextStep()} }
          type="submit"
        >
          Suivant
        </Button>
      </form>
    </>
  );
};

export default StepFour;
