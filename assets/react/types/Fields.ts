export interface BaseFieldProps {
  name: string;
  label?: string;
  placeholder?: string;
  control: any;
  required?: boolean;
  disabled?: boolean;
  defaultValue?: any;
  id?: any;
  value?: any;
  containerClass?: string;
  handleChange?: Function
  autoCompleteChoice?: boolean
}

export enum Fields {
  TEXT = 'text',
  SELECT = 'select',
  TEXTAREA = 'textarea',
  NUMBER = 'number',
  EMAIL = 'email',
  PHONE = 'tel',
}
