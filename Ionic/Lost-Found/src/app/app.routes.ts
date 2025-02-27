import { Routes } from '@angular/router';
import { HomePage } from './home/home.page';
import { RegisterPage } from './register/register.page';
import { ResetPage } from './resetpass/resetpass.page';
import { MainPage } from './main-page/main-page.page';
import { ChangepassPage } from './changepass/changepass.page';
import { AddItemPage } from './add-item/add-item.page';
import { ListPage } from './list/list.page';

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
    loadComponent: () => import('./main-page/main-page.page').then( m => m.MainPage)
  },
  {
    path: 'changepass',
    loadComponent: () => import('./changepass/changepass.page').then( m => m.ChangepassPage)
  },
  {
    path: 'add-item',
    loadComponent: () => import('./add-item/add-item.page').then( m => m.AddItemPage)
  },
  {
    path: 'list',
    loadComponent: () => import('./list/list.page').then( m => m.ListPage)
  },
  {
    path: 'search',
    loadComponent: () => import('./search/search.page').then( m => m.SearchPage)
  },
  {
    path: 'update',
    loadComponent: () => import('./update/update.page').then( m => m.UpdatePage)
  },
  {
    path: 'delete',
    loadComponent: () => import('./delete/delete.page').then( m => m.DeletePage)
  },
  
];
