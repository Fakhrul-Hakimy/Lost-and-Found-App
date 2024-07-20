import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import {
  IonContent,
  IonHeader,
  IonTitle,
  IonToolbar,
  IonButtons,
  IonBackButton,
  IonButton,
  IonIcon
} from '@ionic/angular/standalone';

@Component({
  selector: 'app-delete',
  templateUrl: './delete.page.html',
  styleUrls: ['./delete.page.scss'],
  standalone: true,
  imports: [
    CommonModule,
    FormsModule,
    IonContent,
    IonHeader,
    IonTitle,
    IonToolbar,
    IonButtons,
    IonBackButton,
    IonButton,
    IonIcon
  ]
})
export class DeletePage implements OnInit {

  constructor() { }

  ngOnInit() { }

}
