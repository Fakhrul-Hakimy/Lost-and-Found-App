import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { IonicModule, ToastController } from '@ionic/angular';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { NavController } from '@ionic/angular';
import { RouterModule } from '@angular/router';

@Component({
  selector: 'app-main-page',
  templateUrl: 'main-page.page.html',
  styleUrls: ['main-page.page.scss'],
  standalone: true,
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    HttpClientModule,
    RouterModule
  ],
})
export class MainPage implements OnInit {

  constructor(private navCtrl: NavController) {} // Inject NavController

  ngOnInit() {
    this.checkLogin();
  }

  ionViewWillEnter() {
    this.checkLogin();
  }

  checkLogin() {
    if (!this.isLoggedIn()) {
      this.navCtrl.navigateRoot('/home');
    }
  }

  // Method to check if the user is logged in
  isLoggedIn(): boolean {
    const username = localStorage.getItem('username');
    const email = localStorage.getItem('email');
    return username !== null && email !== null;
  }

  logout() {
    localStorage.removeItem('username');
    localStorage.removeItem('email');
    this.navCtrl.navigateRoot('/home');
  }
}
