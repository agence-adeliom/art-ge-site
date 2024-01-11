import React, { InputHTMLAttributes, useEffect, useState } from 'react';
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
import { useWizard } from '@hooks/useWizard';
import { StepAnim } from '@components/Animation/Step';
import { RoutePaths } from '@react/config/routes';

interface DataFields {
  company: string;
  address: string;
  zip: string;
  city: string;
}

interface LocationProps {
  zip: string;
  name: string;
}

//@TODO mise en place de l'url de prod
const autoCompleteAPI = 'https://art-grand-est.ddev.site/api/insee/';

const StepFour = () => {
  const { wizard, feedRepondantAndGoToNextStep, prevStep } = useWizard();

  const repondant = wizard?.reponse?.repondant;

  // Function d'autocompletion du zip
  const [zipCode, setZipCode] = useState<string>('');
  const [debouncedZipCode, setDebouncedZipCode] = useState<string>('');
  const [zipResult, setZipResult] = useState<LocationProps[]>([]);
  const [openDropdown, setOpenDropdown] = useState(false);

  const { textRequired, zipCodeRequired } = useValidation();

  const schema: ObjectSchema<DataFields> = yup.object().shape({
    company: textRequired,
    address: textRequired,
    zip: zipCodeRequired,
    city: textRequired,
  });

  const {
    handleSubmit,
    control,
    setValue,
    formState: { isValid },
  } = useForm<DataFields>({
    mode: 'onBlur',
    resolver: yupResolver(schema),
  });

  const selectLocation = (location: LocationProps, e: React.MouseEvent) => {
    e.preventDefault();
    setValue('zip', location.zip, {
      shouldValidate: true,
    });
    setValue('city', location.name, {
      shouldValidate: true,
    });
    setOpenDropdown(false);
    setZipResult([]);
  };

  // Mettre à jour le debouncedZipCode après un délai
  useEffect(() => {
    const timerId = setTimeout(() => {
      setDebouncedZipCode(zipCode);
    }, 500); // Délai de 500 ms

    return () => clearTimeout(timerId);
  }, [zipCode]);

  useEffect(() => {
    const fetchLocations = async (zip: string) => {
      try {
        setOpenDropdown(true);
        const response = await fetch(`${autoCompleteAPI}${zip}`);
        const jsonData = await response.json();
        setZipResult(jsonData);
      } catch (error) {
        console.log(error);
      }
    };
    if (debouncedZipCode) {
      fetchLocations(debouncedZipCode);
    }
  }, [debouncedZipCode]);

  const onSubmit: SubmitHandler<DataFields> = data => {
    feedRepondantAndGoToNextStep(data, RoutePaths.FORM);
  };

  return (
    <>
      <StepAnim>
        <form onSubmit={handleSubmit(onSubmit)}>
          <Button
            variant="textOnly"
            icon={'fa-chevron-left'}
            iconSide="left"
            className="!w-fit"
            weight={600}
            onClick={() => prevStep()}
          >
            Retour
          </Button>
          <Heading variant="display-4" className="mt-2 lg:mt-6">
            L’adresse de votre établissement...
          </Heading>
          <Text className="mt-6" color="neutral-500" weight={400} size="sm">
            Ces coordonnées nous permettent de vous situer dans le Grand Est.
          </Text>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6 w-full mt-8">
            <TextInput
              containerClass="col-span-1"
              label={'Établissement'}
              name={'company'}
              type={Fields.TEXT}
              placeholder={'Nom de l’établissement'}
              control={control}
              autoCompleteChoice={false}
              defaultValue={repondant?.company}
            ></TextInput>

            <TextInput
              containerClass="col-span-1"
              label={'Adresse'}
              name={'address'}
              type={Fields.TEXT}
              placeholder={`Ex : 8 rue de l'école`}
              control={control}
              autoCompleteChoice={false}
              defaultValue={repondant?.address}
            ></TextInput>
            <div className="relative col-span-1">
              <TextInput
                label={'Code postal'}
                name={'zip'}
                type={Fields.TEXT}
                placeholder={'Ex : 67000'}
                onChange={e => setZipCode(e.target.value)}
                onBlur={() => setOpenDropdown(false)}
                onFocus={e => setZipCode(e.target.value)}
                control={control}
                autoCompleteChoice={false}
                defaultValue={repondant?.zip}
              ></TextInput>
              {zipResult.length > 0 && openDropdown && (
                <div className="bg-white w-full z-50 max-h-[200px] overflow-auto mt-6 absolute shadow-[0_0_8px_2px_rgba(0,0,0,.10)]">
                  {zipResult.map((item: LocationProps, index: number) => {
                    return (
                      <div
                        className="cursor-pointer last:rounded-b p-4 border-b last:border-none border-neutral-400 flex flex-row gap-1 items-center relative hover:bg-primary-600 hover:text-white trans-default"
                        key={index}
                        onMouseDown={e => selectLocation(item, e)}
                      >
                        {item.zip} {item.name}
                      </div>
                    );
                  })}
                </div>
              )}
            </div>

            <TextInput
              containerClass="col-span-1"
              label={'Ville'}
              name={'city'}
              type={Fields.TEXT}
              placeholder={'Ex : Strasbourg'}
              control={control}
              autoCompleteChoice={false}
              defaultValue={repondant?.city}
            ></TextInput>
          </div>

          <Button
            size="lg"
            className="mt-8"
            icon="fa-minus"
            iconSide="left"
            disabled={!isValid}
            type="submit"
          >
            Suivant
          </Button>
        </form>
      </StepAnim>
    </>
  );
};

export default StepFour;
