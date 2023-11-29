/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: [
    "./assets/**/*.js",
    "./assets/**/*.ts",
    "./assets/**/*.tsx",
    "./templates/*.html.twig",
    "./templates/**/*.html.twig",
  ],
  theme: {
    container: {
      center: true,
      padding: {
        DEFAULT: '1rem',
      },
    },
    extend: {
      fontFamily: {
        text : 'Inter',
        title: 'Cormorant'
      },
      colors: {
        primary : {
          '950': '#256319',
          '800': '#39772D',
          '600': '#57954B',
          '400': '#75B369',
          '200': '#BCE3B5',
          '50': '#EBF5E9',
        },
        secondary : {
          '950': '#387D83',
          '800': '#60A5AB',
          '600': '#74B9BF',
          '400': '#88CDD3',
          '200': '#9DD0D4',
          '50': '#DBEDEF',
        },
        tertiary : {
          '950': '#527369',
          '800': '#7A9B91',
          '600': '#98B9AF',
          '400': '#C0E1D7',
          '200': '#DAF0E9',
          '50': '#E3F0EC',
        },
        neutral : {
          '950' : '#131316',
          '900' : '#18181B',
          '800' : '#27272A',
          '700' : '#3F3F46',
          '600' : '#52525B',
          '500' : '#71717A',
          '400' : '#A1A1AA',
          '300' : '#D4D4D8',
          '200' : '#E4E4E7',
          '100' : '#F4F4F5',
          '50' : '#F9F9F9',
        },
        warning : {
          '950': '#452C03',
          '800': '#92610E',
          '600': '#D98B06',
          '400': '#FBAB24',
          '200': '#FDD28A',
          '50': '#FFF8EB',
        },
        danger : {
          '950': '#450A0A',
          '800': '#991B1B',
          '600': '#DC2626',
          '400': '#F87171',
          '200': '#FECACA',
          '50': '#FEF2F2',
        }
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ]
}

