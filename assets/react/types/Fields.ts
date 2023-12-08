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
  autoCompleteChoice?: boolean;
  onChange?: (e: React.ChangeEvent<HTMLInputElement>) => void;
}

export enum Fields {
  TEXT = 'text',
  SELECT = 'select',
  TEXTAREA = 'textarea',
  NUMBER = 'number',
  EMAIL = 'email',
  PHONE = 'tel',
}
