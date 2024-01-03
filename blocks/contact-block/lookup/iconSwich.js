import { h } from 'preact';

export function iconSwitch(icon) {
  switch (icon) {
    case 'Food':
      return (
        <svg>
          <use href="#food-icon"></use>
        </svg>
      );
    case 'Caring for an Older Adult':
      return (
        <svg>
          <use href="#older-icon"></use>
        </svg>
      );
    case 'Getting a Job':
      return (
        <svg>
          <use href="#jobs-icon"></use>
        </svg>
      );
    case 'Paying Bills':
      return (
        <svg>
          <use href="#bills-icon"></use>
        </svg>
      );
    case 'Supporting my Child':
      return (
        <svg>
          <use href="#child-support-icon"></use>
        </svg>
      );
    case 'Child Care':
      return (
        <svg>
          <use href="#childcare-icon"></use>
        </svg>
      );
    default:
      return null;
  }
}
