#ifndef MATHECLASS_INCLUDE
#define MATHECLASS_INCLUDE

#include <math.h>
#include <vector>
#include <algorithm>
/*
* MathClass
*
*/

class Math {
private:
	static double Angle(double radian);
	static double Radian(double angle);
public:
	static double Pi; //�~����
	template<typename T> static double Sin(T angle);
	template<typename T> static double Cos(T angle);
	template<typename T> static double Tan(T angle);
	template<typename T> static double aSin(T num);
	template<typename T> static double aCos(T num);
	template<typename T> static double aTan(T num);
	template<typename T> static T limit(T num, T min, T max);
	template<typename T> static double cosSimilarity(std::vector<T> vector1, std::vector<T> vector2);
	template<typename T> static double eucDistance(std::vector<T> vector1, std::vector<T> vector2);
};

template<typename T> double Math::Sin(T angle){
	return sin(Radian(angle));
}
template<typename T> double Math::Cos(T angle){
	return cos(Radian(angle));
}
template<typename T> double Math::Tan(T angle){
	return tan(Radian(angle));
}
template<typename T> double Math::aSin(T num){
	return Angle(asin(num));
}
template<typename T> double Math::aCos(T num){
	return Angle(acos(num));
}
template<typename T> double Math::aTan(T num){
	return Angle(atan(num));
}

//�l�̂Ƃ�͈͂𐧌�
template<typename T> T Math::limit(T num, T min, T max){
	if(num > max){
		return max;
	}else if(num < min){
		return min;
	}else{
		return num;
	}
}

template<typename T> double Math::cosSimilarity(std::vector<T> vector1, std::vector<T> vector2){
	double p_v1 = 0.0;
	double p_v2 = 0.0;
	double p_v1v2 = 0.0;
	int v1_size = (signed)vector1.size();
	int v2_size = (signed)vector2.size();

	for(int i = 0 ; i < v1_size && i < v2_size ; i++){
		p_v1 += pow((double)vector1[i],2);
		p_v2 += pow((double)vector2[i],2);
		p_v1v2 += vector1[i] * vector2[i];
	}

	return p_v1v2 / (sqrt(p_v1) * sqrt(p_v2));
}

template<typename T> double Math::eucDistance(std::vector<T> vector1, std::vector<T> vector2){
	double result = 0.0;
	int v1_size = (signed)vector1.size();
	int v2_size = (signed)vector2.size();
	for(int i = 0 ; i < v1_size && i < v2_size ; i++){
		result += pow((double)(vector1[i] - vector2[i]),2);
	}
	return sqrt(result);
}

#endif