import * as yup from 'yup';

export enum ValidationString {
  REQUIRED = 'Ce champ est obligatoire.',
  EMAIL = 'Veuillez entrer une adresse email valide.',
  ZIPCODE = 'Veuillez entrer un code postal de la région Grand Est valide.',
  PHONE = 'Veuillez entrer un numéro de téléphone valide.',
  CONSENT = 'Veuillez accepter les conditions',
}

const phoneRegex: RegExp =
  /^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$/;

const phoneOptionalRegex: RegExp =
  /^$|^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$/;

export const useValidation = () => {
  return {
    textOptional: yup.string().notRequired(),
    textRequired: yup.string().required(ValidationString.REQUIRED),
    booleanNumberRequired: yup
      .number()
      .required(ValidationString.REQUIRED)
      .min(0)
      .max(1),
    zipCodeRequired: yup
      .string()
      .required(ValidationString.REQUIRED)
      .matches(
        /^(08|10|51|52|54|55|57|67|68|88)[0-9]+$/,
        ValidationString.ZIPCODE,
      )
      .min(5, ValidationString.ZIPCODE)
      .max(5, ValidationString.ZIPCODE),
    emailRequired: yup
      .string()
      .email(ValidationString.EMAIL)
      .required(ValidationString.REQUIRED),
    phoneOptional: yup
      .string()
      .notRequired()
      .matches(phoneOptionalRegex, ValidationString.PHONE),
    phoneRequired: yup
      .string()
      .required(ValidationString.REQUIRED)
      .matches(phoneRegex, ValidationString.PHONE),
    arrayNullable: yup.array().nullable(),
    objectRequired: yup.object().required(),
    booleanNullable: yup.boolean(),
    booleanRequired: yup
      .boolean()
      .oneOf([true], ValidationString.REQUIRED)
      .required(ValidationString.REQUIRED),
    consentRequired: yup
      .boolean()
      .oneOf([true], ValidationString.CONSENT)
      .required(ValidationString.CONSENT),
  };
};
