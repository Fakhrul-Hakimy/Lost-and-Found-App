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
  weatherData: any;

  constructor(private navCtrl: NavController, private http: HttpClient) {}

  ngOnInit() {
    this.checkLogin();
    this.loadWeather();
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

  loadWeather() {
    const apiUrl = '/api/v1/current.json?key=3627c39e0ece47a396b163556242406&q=Sintok&aqi=no';
    this.http.get(apiUrl).subscribe((data: any) => {
      this.weatherData = data;
    });
  }
}
