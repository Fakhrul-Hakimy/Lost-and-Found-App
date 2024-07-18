import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class LostItemService {
  private apiUrl = 'http://192.168.155.169/processItem.php';

  constructor(private http: HttpClient) { }

  addItem(data: FormData): Observable<any> {
    return this.http.post<any>(this.apiUrl, data);
  }
}
