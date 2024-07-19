import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { IonicModule } from '@ionic/angular';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { NavController } from '@ionic/angular';
interface Item {
  image_path: string;
  item_name: string;
  category: string;
  description: string;
  date_found: string;
  finder_name: string;
  finder_contact: string;
}

@Component({
  selector: 'app-list',
  templateUrl: './list.page.html',
  styleUrls: ['./list.page.scss'],
  standalone: true,
  imports: [CommonModule, IonicModule, HttpClientModule]
})
export class ListPage implements OnInit {
  paginatedItems: Item[] = [];
  currentPage = 1;
  itemsPerPage = 10;
  totalPages = 1;
  items: Item[] = [];

  constructor(private http: HttpClient,private navCtrl: NavController) {}
  
  ngOnInit() {
    this.checkLogin();
    this.loadItems();
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

  updatePaginatedItems() {
    const startIndex = (this.currentPage - 1) * this.itemsPerPage;
    const endIndex = startIndex + this.itemsPerPage;
    this.paginatedItems = this.items.slice(startIndex, endIndex);
  }

  nextPage() {
    if (this.currentPage < this.totalPages) {
      this.currentPage++;
      this.updatePaginatedItems();
    }
  }

  previousPage() {
    if (this.currentPage > 1) {
      this.currentPage--;
      this.updatePaginatedItems();
    }
  }

  loadItems() {
    this.http.get<Item[]>('http://192.168.155.169/getItem.php').subscribe(
      (response) => {
        this.items = response;
        this.items.forEach(item => {
          if (item.image_path) {
            item.image_path = `http://192.168.155.169/${item.image_path}`;
          }
        });
        this.totalPages = Math.ceil(this.items.length / this.itemsPerPage);
        this.updatePaginatedItems();
      },
      (error) => {
        console.error('Error fetching items:', error);
      }
    );
  }
}
