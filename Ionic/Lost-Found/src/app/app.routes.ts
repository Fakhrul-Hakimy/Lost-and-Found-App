import { Routes } from '@angular/router';
import { HomePage } from './home/home.page';
import { RegisterPage } from './register/register.page';
import { ResetPage } from './resetpass/resetpass.page';

export const routes: Routes = [
  {
    path: 'home',
    loadComponent: () => import('./home/home.page').then((m) => m.HomePage),
  },
  {
    path: '',
    redirectTo: 'home',
    pathMatch: 'full',
  },
  {
    path: 'register',
    loadComponent: () => import('./register/register.page').then(m => m.RegisterPage),
  },
  {
    path: 'resetpass',
    loadComponent: () => import('./resetpass/resetpass.page').then( m => m.ResetPage)
  },
  {
    path: 'main-page',
    loadComponent: () => import('./main-page/main-page.page').then( m => m.MainPagePage)
  },
];
